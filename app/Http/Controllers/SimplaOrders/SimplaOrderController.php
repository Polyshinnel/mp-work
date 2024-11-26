<?php

namespace App\Http\Controllers\SimplaOrders;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class SimplaOrderController extends Controller
{
    public function getOrder(string $externalId): array
    {
        try{
            $url = 'https://liberty-jones.ru/index.php?module=OrderApiView';
            $data = [
                'externalId' => $externalId,
                'api_method' => 'search_order'
            ];
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($data),
                CURLOPT_RETURNTRANSFER => true,
            );
            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response, true);
        } catch (Exception $exception) {
            return [
                'error' => $exception->getMessage(),
                'message' => 'Something went wrong'
            ];
        }
    }
}
