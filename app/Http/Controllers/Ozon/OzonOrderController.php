<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SimplaOrders\SimplaOrderController;
use App\Service\CommonSettingsService;
use App\Service\OzonProcessingService;
use Illuminate\Http\Request;

class OzonOrderController extends Controller
{
    private OzonApi $api;
    private SimplaOrderController $simplaOrderController;
    private CommonSettingsService $commonSettingsService;
    private OzonProcessingService $ozonProcessingService;

    public function __construct(
        OzonApi $api,
        SimplaOrderController $simplaOrderController,
        CommonSettingsService $commonSettingsService,
        OzonProcessingService $ozonProcessingService
    )
    {
        $this->api = $api;
        $this->simplaOrderController = $simplaOrderController;
        $this->commonSettingsService = $commonSettingsService;
        $this->ozonProcessingService = $ozonProcessingService;
    }

    public function addOrderToOrderList(): void
    {
        date_default_timezone_set('Europe/Moscow');
        $currentDate = date("Y-m-d");
        //$dateStart = sprintf('%sT%s.000Z', $currentDate, '00:00:00');
        $dateStart = '2024-11-25T00:00:00.000Z';
        $dateEnd = sprintf('%sT%s.000Z', $currentDate, '23:59:59');
        $warehouses = $this->commonSettingsService->getAllOzonWarehousesIds();
        $statusList = $this->commonSettingsService->getOzonStatusWatchList();

        $ozonOrders = $this->ozonProcessingService->getOzonPackingsByPeriod($this->api, $warehouses, $statusList, $dateStart, $dateEnd);
        $simplaOrders = $this->getSimplaOrderProcessing($ozonOrders);
        $dbOrders = $this->ozonProcessingService->processingPostingsToDb($simplaOrders);
        $this->ozonProcessingService->createOzonOrders($dbOrders);

    }

    public function getSimplaOrderProcessing($ozonOrders)
    {
        $simplaResult = [];
        if($ozonOrders){
            foreach ($ozonOrders as $order) {
                $simplaInfo = $this->simplaOrderController->getOrder($order['ozon_posting_id']);
                if($simplaInfo) {
                    $order['site_order_id'] = $simplaInfo['order_id'];
                    $order['site_status_id'] = $simplaInfo['status'];
                    $order['db_name'] = $simplaInfo['database_name'];
                    $order['site_label_id'] = $simplaInfo['labels']['label_id'];
                    $simplaResult[] = $order;
                }
            }
        }

        return $simplaResult;
    }
}
