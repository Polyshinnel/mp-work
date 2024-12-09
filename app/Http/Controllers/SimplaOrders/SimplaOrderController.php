<?php

namespace App\Http\Controllers\SimplaOrders;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

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

    public function getInternalOrderList(array $orderList)
    {
        try{
            $url = 'https://paolareinas.ru/index.php?module=OrderApiView';
            $data = [
                'order_list' => $orderList,
                'api_method' => 'search_internal_order_list'
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

    public function getInternalOrder(string $internalOrderId, string $database)
    {
        try{
            $url = 'https://paolareinas.ru/index.php?module=OrderApiView';
            $data = [
                'internal_id' => $internalOrderId,
                'database' => $database,
                'api_method' => 'search_internal_order'
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

    public function updateOrderStatus(string $internalOrderId, string $database, string $status)
    {
        try{
            $url = 'https://paolareinas.ru/index.php?module=OrderApiView';
            $data = [
                'internal_id' => $internalOrderId,
                'database' => $database,
                'status' => $status,
                'api_method' => 'update_site_order'
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
