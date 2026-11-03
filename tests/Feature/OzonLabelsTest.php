<?php

namespace Tests\Feature;

use App\Http\Controllers\Ozon\OzonApi;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OzonLabelsTest extends TestCase
{
    public function test_new_label_contract_and_credentials_for_both_accounts(): void
    {
        Http::preventStrayRequests();
        config([
            'ozon.ozon_client_id' => 'main', 'ozon.ozon_api_key' => 'main-key',
            'ozon.ozon_ip_client_id' => 'ip', 'ozon.ozon_ip_api_key' => 'ip-key',
        ]);
        Http::fake([
            '*/v3/posting/fbs/package-label/create' => Http::response([
                'tasks' => [['task_id' => 123, 'task_type' => 'small_label']],
            ]),
            '*/v2/posting/fbs/package-label/get' => Http::response([
                'status' => 'completed', 'file_url' => 'https://example.test/labels.pdf',
            ]),
        ]);
        $api = new OzonApi();
        foreach ([true, false] as $ip) {
            $tasks = json_decode($api->getLabelsTask(['123-1-1'], $ip), true);
            $this->assertSame(123, $tasks['result']['tasks'][0]['task_id']);
            $label = json_decode($api->getLabels('123', $ip), true);
            $this->assertSame('https://example.test/labels.pdf', $label['result']['file_url']);
        }
        $requests = Http::recorded()->pluck(0);
        $this->assertSame(['posting_numbers' => ['123-1-1']], $requests[0]->data());
        $this->assertSame(['task_id' => 123], $requests[1]->data());
        $this->assertTrue($requests[0]->hasHeader('Client-Id', 'ip'));
        $this->assertTrue($requests[1]->hasHeader('Api-Key', 'ip-key'));
        $this->assertTrue($requests[2]->hasHeader('Client-Id', 'main'));
        $this->assertTrue($requests[3]->hasHeader('Api-Key', 'main-key'));
        Http::assertSentCount(4);
    }

    public function test_label_api_errors_are_not_silently_treated_as_pending_tasks(): void
    {
        Http::fake(['*/v3/posting/fbs/package-label/create' => Http::response(['message' => 'Invalid posting'], 400)]);
        $this->expectException(RequestException::class);
        (new OzonApi())->getLabelsTask(['invalid']);
    }
}
