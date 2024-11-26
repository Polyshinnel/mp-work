<?php

namespace App\Http\Controllers\SimplaOrders;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class SimplaOrderController extends Controller
{
    public function getOrder(string $externalId): ?array
    {
        try{
            $url = 'https://paolareinas.ru/index.php?module=OrderApiView';
            $data = [
                'external_id' => $externalId,
                'api_method' => 'search_order'
            ];
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            return json_decode($response, true);
        } catch (Exception $exception) {
            return [
                'error' => $exception->getMessage(),
                'message' => 'Something went wrong'
            ];
        }
    }
}
