<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OzonApi extends Controller
{
    private array $headers;

    public function __construct()
    {
        $clientId = sprintf('Client-Id: %s', config('ozon.ozon_client_id'));
        $apiKey = sprintf('Api-Key: %s', config('ozon.ozon_api_key'));

        $this->headers = [
            'Content-Type: application/json',
            $clientId,
            $apiKey
        ];
    }
    public function getFilteredPostings($dateStart, $dateEnd, $warehouseId, $status)
    {
        $url = 'https://api-seller.ozon.ru/v3/posting/fbs/list';
        $jsonArr = [
            'dir' => 'DESC',
            'filter' => [
                'since' => $dateStart,
                'status' => $status,
                'to' => $dateEnd,
                'warehouse_id' => [
                    $warehouseId
                ]
            ],
            'limit' => 500,
            'offset' => 0,
        ];

        return $this->getPostJsonRequest($url, $this->headers, json_encode($jsonArr));
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
