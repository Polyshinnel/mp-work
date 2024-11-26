<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SimplaOrders\SimplaOrderController;
use App\Service\CommonSettingsService;
use Illuminate\Http\Request;

class OzonOrderController extends Controller
{
    private OzonApi $api;
    private SimplaOrderController $simplaOrderController;
    private CommonSettingsService $commonSettingsService;

    public function __construct(
        OzonApi $api,
        SimplaOrderController $simplaOrderController,
        CommonSettingsService $commonSettingsService
    )
    {
        $this->api = $api;
        $this->simplaOrderController = $simplaOrderController;
        $this->commonSettingsService = $commonSettingsService;
    }

    public function getOrderList(): array
    {
        $currentDate = date("Y-m-d");
        $dateStart = sprintf('%sT%s.000Z', $currentDate, '00:00:00');
        $dateEnd = sprintf('%sT%s.000Z', $currentDate, '23:59:59');
        $warehouses = $this->commonSettingsService->getAllOzonWarehousesIds();
        $statusList = $this->commonSettingsService->getOzonStatusWatchList();
        $rawResults = [];

        if($warehouses && $statusList){
            foreach($warehouses as $warehouse){
                foreach($statusList as $status){
                    $result = $this->api->getFilteredPostings($dateStart, $dateEnd, $warehouse, $status);
                    if($result){
                        $result = json_decode($result, true);
                        if($result['result']['postings']) {
                            foreach($result['result']['postings'] as $posting){
                                $rawResults[$warehouse][$status][] = $posting;
                            }
                        }
                    }
                }
            }
        }

        return $rawResults;
    }

    public function processingOzonOrders()
    {
        $orderList = $this->getOrderList();
        $processedOrders = [];
        if($orderList){
            foreach($orderList as $warehouse => $statusOrderArr){
                $warehouseOzonId = $warehouse;
                if($statusOrderArr) {
                    foreach($statusOrderArr as $ozonStatus => $orderArr){
                        foreach ($orderArr as $order) {
                            $productCount = 0;
                            if($order['products']) {
                                foreach($order['products'] as $product){
                                    $productCount += $product['quantity'];
                                }
                            }
                            $processedOrders[] = [
                                'ozon_warehouse_id' => $warehouseOzonId,
                                'ozon_status' => $ozonStatus,
                                'ozon_posting_id' => $order['posting_number'],
                                'ozon_order_id' => $order['order_id'],
                                'date_create' => $order['in_process_at'],
                                'product_count' => $productCount,
                            ];
                        }

                    }
                }
            }
        }
        dd($processedOrders);
        return $processedOrders;
    }

    public function getSimplaOrderProcessing()
    {
        $simplaResult = [];
        $ozonOrderList = $this->processingOzonOrders();
        if($ozonOrderList){
            foreach ($ozonOrderList as $order) {

            }
        }
    }
}
