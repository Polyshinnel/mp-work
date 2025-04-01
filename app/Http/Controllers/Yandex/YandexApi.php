<?php

namespace App\Http\Controllers\Yandex;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class YandexApi extends Controller
{
    public function getDeliveryLabel(string $campaignId, string $orderId)
    {
        $token = config('yandex_config.yandex_api_key');
        $url = sprintf('https://api.partner.market.yandex.ru/campaigns/%s/orders/%s/delivery/labels.json?format=A9_HORIZONTALLY', $campaignId, $orderId);
        $data = $this->sendYandexRequest($url, $token);
        if($data)
        {
            $fileName = time().'.pdf';
            $info = Storage::disk('yandex_labels')->put($fileName, $data);
            return 'yandex-labels/'.$fileName;
        }
        return false;
    }
    public function sendYandexRequest($url, $token)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Api-Key: $token"
        ]);
        return curl_exec($ch);
    }
}
