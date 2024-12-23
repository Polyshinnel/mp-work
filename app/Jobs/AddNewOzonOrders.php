<?php

namespace App\Jobs;

use App\Http\Controllers\Ozon\OzonApi;
use App\Http\Controllers\SimplaOrders\SimplaOrderController;
use App\Service\CommonSettingsService;
use App\Service\OzonProcessingService;
use DateTime;

class AddNewOzonOrders
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
        $currentDate= new DateTime();
        $today = $currentDate->format('Y-m-d');
        $currentDate->modify('-2 day');
        $yesterday = $currentDate->format('Y-m-d');
        $dateStart = sprintf('%sT%s.000Z', $yesterday, '00:00:00');
        //$dateStart = '2024-11-25T00:00:00.000Z';
        $dateEnd = sprintf('%sT%s.000Z', $today, '23:59:59');
        $warehouses = $this->commonSettingsService->getAllOzonWarehousesIds();
        $statusList = $this->commonSettingsService->getOzonStatusWatchList();

        $ozonOrders = $this->ozonProcessingService->getOzonPackingsByPeriod($this->api, $warehouses, $statusList, $dateStart, $dateEnd);
        $simplaOrders = $this->getSimplaOrderProcessing($ozonOrders);
        $dbOrders = $this->ozonProcessingService->processingPostingsToDb($simplaOrders);
        $this->ozonProcessingService->createOzonOrders($dbOrders);

    }

    public function getSimplaOrderProcessing($ozonOrders): array
    {
        $simplaResult = [];
        if($ozonOrders){
            foreach ($ozonOrders as $order) {
                $simplaInfo = $this->simplaOrderController->getOrder($order['ozon_posting_id']);

                if($simplaInfo) {
                    if(isset($simplaInfo['labels']))
                    {
                        $order['site_order_id'] = $simplaInfo['order_id'];
                        $order['site_status_id'] = $simplaInfo['status'];
                        $order['db_name'] = $simplaInfo['database_name'];
                        $order['site_label_id'] = $simplaInfo['labels']['label_id'];
                        $simplaResult[] = $order;
                    }

                }
            }
        }

        return $simplaResult;
    }
}
