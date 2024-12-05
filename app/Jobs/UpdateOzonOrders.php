<?php

namespace App\Jobs;

use App\Http\Controllers\Ozon\OzonApi;
use App\Http\Controllers\SimplaOrders\SimplaOrderController;
use App\Service\CommonSettingsService;
use App\Service\OzonOrderService;
use App\Service\OzonProcessingService;
use App\Service\OzonSettingsService;

class UpdateOzonOrders
{
    private OzonApi $api;
    private SimplaOrderController $simplaOrderController;
    private CommonSettingsService $commonSettingsService;
    private OzonProcessingService $ozonProcessingService;
    private OzonOrderService $ozonOrderService;
    private OzonSettingsService $ozonSettingsService;

    public function __construct(
        OzonApi $api,
        SimplaOrderController $simplaOrderController,
        CommonSettingsService $commonSettingsService,
        OzonProcessingService $ozonProcessingService,
        OzonOrderService $ozonOrderService,
        OzonSettingsService $ozonSettingsService
    )
    {
        $this->api = $api;
        $this->simplaOrderController = $simplaOrderController;
        $this->commonSettingsService = $commonSettingsService;
        $this->ozonProcessingService = $ozonProcessingService;
        $this->ozonOrderService = $ozonOrderService;
        $this->ozonSettingsService = $ozonSettingsService;
    }

    public function updateOzonStatus(): void
    {
        $warehouses = $this->ozonSettingsService->getOzonWarehouses();
        $statusList = $this->ozonSettingsService->getOzonWatchableStatusNames();
        $formattedPostings = [];
        date_default_timezone_set('Europe/Moscow');
        $currentDate = date("Y-m-d");
        $dateStart = sprintf('%sT%s.000Z', $currentDate, '00:00:00');
        //$dateStart = '2024-11-25T00:00:00.000Z';
        $dateEnd = sprintf('%sT%s.000Z', $currentDate, '23:59:59');
        foreach ($statusList as $status) {
            $result = $this->api->getFilteredPostings($dateStart, $dateEnd, $warehouses, $status);
            if($result)
            {
                $result = json_decode($result, true);
                if(isset($result['result']['postings'])) {
                    $postings = $result['result']['postings'];
                    foreach($postings as $posting) {
                        $formattedPostings[] = [
                            'posting_number' => $posting['posting_number'],
                            'status' => $posting['status'],
                        ];
                    }
                }
            }
        }

        if($formattedPostings) {
            $this->ozonProcessingService->updateOzonOrder($formattedPostings);
        }
    }

    public function updateSiteStatus(): void
    {
        $ozonOrders = $this->ozonProcessingService->getOzonWatchableOrders();
        $commonSettings = $this->commonSettingsService->getCommonSettingsAssociativeData();
        if(!$ozonOrders->isEmpty()) {
            foreach ($ozonOrders as $ozonOrder) {
                $ozonOrderSite = $ozonOrder->siteInfo;
                $database = $ozonOrderSite->db_name;
                $result = $this->simplaOrderController->getInternalOrder($ozonOrder->site_order_id, $database);
                if($result) {
                    $this->ozonProcessingService->updateOzonOrderSite($ozonOrder->id, $result, $commonSettings);
                }
            }
        }
    }

    public function updateSiteStatusArr(): void
    {
        $ozonOrders = $this->ozonProcessingService->getOzonWatchableOrders();
        $commonSettings = $this->commonSettingsService->getCommonSettingsAssociativeData();
        $simplaResults = [];
        if(!$ozonOrders->isEmpty()) {
            $orderBlocks = $this->ozonOrderService->getOzonOrderListBlock($ozonOrders);
            if($orderBlocks)
            {
                foreach ($orderBlocks as $orderList)
                {
                    $result = $this->simplaOrderController->getInternalOrderList($orderList);
                    if($result){
                        foreach ($result as $item)
                        {
                            $simplaResults[] = $item;
                        }
                    }
                }
            }
        }
        if($simplaResults){
            foreach ($simplaResults as $result)
            {
                $this->ozonProcessingService->updateOzonOrderSiteBySiteInfo($result['external_cs_id'], $result, $commonSettings);
            }
        }
    }
}
