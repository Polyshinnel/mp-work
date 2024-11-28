<?php

namespace App\Jobs;

use App\Http\Controllers\Ozon\OzonApi;
use App\Http\Controllers\SimplaOrders\SimplaOrderController;
use App\Service\CommonSettingsService;
use App\Service\OzonProcessingService;

class UpdateOzonOrders
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
                $result = $this->simplaOrderController->getOrder($ozonOrder->ozon_posting_id);
                $this->ozonProcessingService->updateOzonOrderSite($ozonOrder->id, $result, $commonSettings);
            }
        }
    }
}
