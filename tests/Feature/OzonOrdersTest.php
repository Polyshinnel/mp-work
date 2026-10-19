<?php

namespace Tests\Feature;

use App\Http\Controllers\Ozon\OzonApi;
use App\Http\Controllers\SimplaOrders\SimplaOrderController;
use App\Jobs\UpdateOzonOrders;
use App\Models\OzonOrder;
use App\Repostory\Ozon\OzonRepository;
use App\Repostory\OzonSettings\OzonSettingsRepository;
use App\Service\CommonSettingsService;
use App\Service\OzonOrderService;
use App\Service\OzonProcessingService;
use App\Service\OzonProductService;
use App\Service\OzonSettingsService;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;
use UnexpectedValueException;

class OzonOrdersTest extends TestCase
{
    private const URL = 'https://api-seller.ozon.ru/v4/posting/fbs/list';
    private const SINCE = '2026-10-16T00:00:00.000Z';
    private const TO = '2026-10-19T23:59:59.000Z';

    protected function setUp(): void
    {
        parent::setUp();

        config(['ozon.ozon_client_id' => 'test-client', 'ozon.ozon_api_key' => 'test-key']);
        Http::preventStrayRequests();
    }

    public function test_fetches_all_pages_using_the_v4_request_and_response_format(): void
    {
        $firstPage = array_fill(0, 100, $this->posting('first'));
        $lastPosting = $this->posting('last');
        Http::fake([self::URL => Http::sequence()
            ->push(['postings' => $firstPage, 'has_next' => true, 'cursor' => 'opaque-cursor'])
            ->push(['postings' => [$lastPosting], 'has_next' => false, 'cursor' => ''])]);

        $postings = (new OzonApi())->getFilteredPostings(self::SINCE, self::TO, [3092889984], 'awaiting_packaging');

        $this->assertCount(101, $postings);
        $this->assertSame($lastPosting, $postings[100]);
        $requests = Http::recorded()->pluck(0);
        $expected = [
            'sort_dir' => 'DESC',
            'filter' => [
                'since' => self::SINCE,
                'statuses' => ['awaiting_packaging'],
                'to' => self::TO,
                'warehouse_ids' => ['3092889984'],
            ],
            'limit' => 100,
            'cursor' => '',
        ];
        $this->assertSame($expected, $requests[0]->data());
        $expected['cursor'] = 'opaque-cursor';
        $this->assertSame($expected, $requests[1]->data());
        Http::assertSentCount(2);
        Http::assertSent(fn (Request $request) => $request->url() === self::URL
            && $request->hasHeader('Client-Id', 'test-client')
            && $request->hasHeader('Api-Key', 'test-key'));
    }

    public function test_empty_posting_list_is_a_successful_result(): void
    {
        Http::fake([self::URL => Http::response(['postings' => [], 'has_next' => false])]);

        $this->assertSame([], (new OzonApi())->getFilteredPostings(self::SINCE, self::TO, [], 'delivered'));
        Http::assertSentCount(1);
    }

    public function test_http_errors_on_later_pages_do_not_return_a_partial_list(): void
    {
        Http::fake([self::URL => Http::sequence()
            ->push(['postings' => [$this->posting('first')], 'has_next' => true, 'cursor' => 'next'])
            ->push(['message' => 'Service unavailable'], 503)]);

        $this->expectException(RequestException::class);
        (new OzonApi())->getFilteredPostings(self::SINCE, self::TO, [], 'delivered');
    }

    /** @dataProvider invalidResponses */
    public function test_invalid_responses_fail_instead_of_looking_like_no_orders(array $response): void
    {
        Http::fake([self::URL => Http::response($response)]);

        $this->expectException(UnexpectedValueException::class);
        (new OzonApi())->getFilteredPostings(self::SINCE, self::TO, [], 'delivered');
    }

    public static function invalidResponses(): array
    {
        return [
            'old envelope' => [['result' => ['postings' => []], 'has_next' => false]],
            'missing pagination flag' => [['postings' => []]],
            'missing cursor' => [['postings' => [], 'has_next' => true]],
            'empty cursor' => [['postings' => [], 'has_next' => true, 'cursor' => '']],
        ];
    }

    public function test_repeated_cursor_stops_pagination(): void
    {
        Http::fake([self::URL => Http::sequence()
            ->push(['postings' => [], 'has_next' => true, 'cursor' => 'same'])
            ->push(['postings' => [], 'has_next' => true, 'cursor' => 'same'])]);

        $this->expectException(UnexpectedValueException::class);
        (new OzonApi())->getFilteredPostings(self::SINCE, self::TO, [], 'delivered');
    }

    public function test_import_maps_v4_products_and_skips_existing_orders_across_pages(): void
    {
        Http::fake([self::URL => Http::sequence()
            ->push(['postings' => [$this->posting('existing')], 'has_next' => true, 'cursor' => 'next'])
            ->push(['postings' => [$this->posting('new')], 'has_next' => false])]);
        $repository = Mockery::mock(OzonRepository::class);
        $repository->shouldReceive('getFilteredOzonPacking')
            ->with([['ozon_posting_id', '=', 'existing']])->once()->andReturn(new OzonOrder());
        $repository->shouldReceive('getFilteredOzonPacking')
            ->with([['ozon_posting_id', '=', 'new']])->once()->andReturnNull();
        $service = new OzonProcessingService(
            $repository,
            Mockery::mock(OzonSettingsRepository::class),
            Mockery::mock(CommonSettingsService::class),
            Mockery::mock(OzonSettingsService::class),
            Mockery::mock(OzonProductService::class),
        );

        $orders = $service->getOzonPackingsByPeriod(
            new OzonApi(), ['3092889984'], ['awaiting_packaging'], self::SINCE, self::TO,
        );

        $this->assertSame([[
            'ozon_warehouse_id' => 3092889984,
            'ozon_status' => 'awaiting_packaging',
            'ozon_posting_id' => 'new',
            'ozon_order_id' => 33301885134,
            'date_create' => self::SINCE,
            'product_count' => 2,
            'products' => [['offer_id' => 'article', 'quantity' => 2, 'name' => 'Product', 'sku' => 1904686181]],
        ]], $orders);
    }

    public function test_status_updates_use_all_pages_and_reset_cursor_for_each_status(): void
    {
        Http::fake([self::URL => Http::sequence()
            ->push(['postings' => [$this->posting('first')], 'has_next' => true, 'cursor' => 'next'])
            ->push(['postings' => [$this->posting('second')], 'has_next' => false])
            ->push(['postings' => [$this->posting('third', 'delivered')], 'has_next' => false])]);
        $settings = Mockery::mock(OzonSettingsService::class);
        $settings->shouldReceive('getOzonWarehouses')->once()->andReturn(['3092889984']);
        $settings->shouldReceive('getOzonWatchableStatusNames')->once()->andReturn(['awaiting_packaging', 'delivered']);
        $processing = Mockery::mock(OzonProcessingService::class);
        $processing->shouldReceive('updateOzonOrder')->once()->with([
            ['posting_number' => 'first', 'status' => 'awaiting_packaging'],
            ['posting_number' => 'second', 'status' => 'awaiting_packaging'],
            ['posting_number' => 'third', 'status' => 'delivered'],
        ]);
        $job = new UpdateOzonOrders(
            new OzonApi(),
            Mockery::mock(SimplaOrderController::class),
            Mockery::mock(CommonSettingsService::class),
            $processing,
            Mockery::mock(OzonOrderService::class),
            $settings,
        );

        $job->updateOzonStatus();

        Http::assertSentCount(3);
        $requests = Http::recorded()->pluck(0);
        $this->assertSame('', $requests[2]['cursor']);
        $this->assertSame(['delivered'], $requests[2]['filter']['statuses']);
    }

    private function posting(string $number, string $status = 'awaiting_packaging'): array
    {
        return [
            'posting_number' => $number,
            'order_id' => 33301885134,
            'status' => $status,
            'in_process_at' => self::SINCE,
            'products' => [[
                'offer_id' => 'article',
                'name' => 'Product',
                'sku' => 1904686181,
                'quantity' => 2,
                'price' => ['amount' => '1200.00', 'currency' => 'RUB'],
            ]],
        ];
    }
}
