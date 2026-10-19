<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use UnexpectedValueException;

class OzonApi extends Controller
{
    private array $headers;

    public function __construct(bool $ip = null)
    {
        $clientId = sprintf('Client-Id: %s', config('ozon.ozon_client_id'));
        $apiKey = sprintf('Api-Key: %s', config('ozon.ozon_api_key'));

        $this->headers = [
            'Content-Type: application/json',
            $clientId,
            $apiKey
        ];
    }
    public function getFilteredPostings(string $dateStart, string $dateEnd, array $warehouses, string $status): array
    {
        $url = 'https://api-seller.ozon.ru/v4/posting/fbs/list';
        $jsonArr = [
            'sort_dir' => 'DESC',
            'filter' => [
                'since' => $dateStart,
                'statuses' => [$status],
                'to' => $dateEnd,
                'warehouse_ids' => array_values(array_map('strval', $warehouses)),
            ],
            'limit' => 100,
            'cursor' => '',
        ];

        $postings = [];
        $seenCursors = [];

        do {
            $result = Http::withHeaders([
                'Client-Id' => config('ozon.ozon_client_id'),
                'Api-Key' => config('ozon.ozon_api_key'),
            ])->acceptJson()->connectTimeout(10)->timeout(60)
                ->post($url, $jsonArr)->throw()->json();

            if (!is_array($result) || !isset($result['postings']) || !is_array($result['postings'])
                || !isset($result['has_next']) || !is_bool($result['has_next'])) {
                throw new UnexpectedValueException('Ozon FBS v4 returned an invalid posting list.');
            }

            $postings = array_merge($postings, $result['postings']);

            if ($result['has_next']) {
                $cursor = $result['cursor'] ?? null;
                if (!is_string($cursor) || $cursor === '' || isset($seenCursors[$cursor])) {
                    throw new UnexpectedValueException('Ozon FBS v4 returned an invalid pagination cursor.');
                }

                $seenCursors[$cursor] = true;
                $jsonArr['cursor'] = $cursor;
            }
        } while ($result['has_next']);

        return $postings;
    }

    public function getWarehouseList()
    {
        $url = 'https://api-seller.ozon.ru/v1/warehouse/list';
    }

    public function getPostings(string $postingId)
    {
        $url = 'https://api-seller.ozon.ru/v3/posting/fbs/get';
        $jsonArr = [
            'posting_number' => $postingId
        ];
        return $this->getPostJsonRequest($url, $this->headers, json_encode($jsonArr));
    }

    public function getLabelsTask(array $postings, bool $ozonIp = false): bool|string
    {
        if($ozonIp)
        {
            $clientId = sprintf('Client-Id: %s', config('ozon.ozon_ip_client_id'));
            $apiKey = sprintf('Api-Key: %s', config('ozon.ozon_ip_api_key'));

            $this->headers = [
                'Content-Type: application/json',
                $clientId,
                $apiKey
            ];
        }
        $postingsArr = ['posting_number' => $postings];
        $url = 'https://api-seller.ozon.ru/v2/posting/fbs/package-label/create';
        return $this->getPostJsonRequest($url, $this->headers, json_encode($postingsArr));
    }

    public function getLabels(string $taskId, bool $ozonIp = false): bool|string
    {
        if($ozonIp)
        {
            $clientId = sprintf('Client-Id: %s', config('ozon.ozon_ip_client_id'));
            $apiKey = sprintf('Api-Key: %s', config('ozon.ozon_ip_api_key'));

            $this->headers = [
                'Content-Type: application/json',
                $clientId,
                $apiKey
            ];
        }
        $postingsArr = ['task_id' => $taskId];
        $url = 'https://api-seller.ozon.ru/v1/posting/fbs/package-label/get';
        return $this->getPostJsonRequest($url, $this->headers, json_encode($postingsArr));
    }

    public function getFilteredProducts($offerId)
    {
        $url = 'https://api-seller.ozon.ru/v3/product/list';

        $filter = [
            "filter" => [
                "offer_id" => [
                    $offerId
                ]
            ],
            "limit" => 1
        ];
        return $this->getPostJsonRequest($url, $this->headers, json_encode($filter));
    }

    public function orderMarkAsShipped(string $postingNumber, string $productId, int $quantity)
    {
        $url = 'https://api-seller.ozon.ru/v4/posting/fbs/ship';
        $jsonArr = [
            'packages' => [
                [
                    'products' => [
                        [
                            'product_id' => (int)$productId,
                            'quantity' => $quantity
                        ]
                    ]
                ]
            ],
            'posting_number' => $postingNumber,
            'with' => [
                'additional_data' => true
            ]
        ];
        return $this->getPostJsonRequest($url, $this->headers, json_encode($jsonArr));
    }



    private function getPostJsonRequest($url,$headers, $json): bool|string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
