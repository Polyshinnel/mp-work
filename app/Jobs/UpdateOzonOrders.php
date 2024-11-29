<?php

namespace App\Jobs;

use App\Http\Controllers\Ozon\OzonApi;
use App\Http\Controllers\SimplaOrders\SimplaOrderController;
use App\Service\CommonSettingsService;
use App\Service\OzonOrderService;
use App\Service\OzonProcessingService;

class UpdateOzonOrders
{
    private OzonApi $api;
    private SimplaOrderController $simplaOrderController;
    private CommonSettingsService $commonSettingsService;
    private OzonProcessingService $ozonProcessingService;
    private OzonOrderService $ozonOrderService;

    public function __construct(
        OzonApi $api,
        SimplaOrderController $simplaOrderController,
        CommonSettingsService $commonSettingsService,
        OzonProcessingService $ozonProcessingService,
        OzonOrderService $ozonOrderService
    )
    {
        $this->api = $api;
        $this->simplaOrderController = $simplaOrderController;
        $this->commonSettingsService = $commonSettingsService;
        $this->ozonProcessingService = $ozonProcessingService;
        $this->ozonOrderService = $ozonOrderService;
    }

    public function updateOzonStatus(): void
    {
        $ozonOrders = $this->ozonProcessingService->getOzonWatchableOrders();
        if(!$ozonOrders->isEmpty()) {
            foreach($ozonOrders as $ozonOrder) {
                $postingId = $ozonOrder->ozon_posting_id;
                $ozonResult = json_decode($this->api->getPostings($postingId), true);
                $this->ozonProcessingService->updateOzonOrder($ozonResult);
            }
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
