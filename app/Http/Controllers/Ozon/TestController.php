<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function __invoke()
    {
        $url = 'https://api-seller.ozon.ru/v2/posting/fbs/package-label';
        $jsonArr = [
            'posting_number' => [
                '47609170-0120-4',
                '49090864-0204-4'
            ]
        ];

        $fileName = time().'.pdf';
        $clientId = sprintf('Client-Id: %s', config('ozon.ozon_client_id'));
        $apiKey = sprintf('Api-Key: %s', config('ozon.ozon_api_key'));
        $json = json_encode($jsonArr);
        $headers = [
            'Content-Type: application/json',
            $clientId,
            $apiKey
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        file_put_contents($fileName, $response);

        $info = Storage::disk('labels')->put($fileName, $response);
        $link = '/public/ozon-labels/'.$fileName;
        return Storage::download($link);
    }
}
