<?php

namespace App\Jobs;

use App\Http\Controllers\Ozon\OzonApi;
use App\Http\Controllers\SimplaOrders\SimplaOrderController;
use App\Service\CommonSettingsService;
use App\Service\OzonProcessingService;

class CheckOldOzonOrders
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
    public function updateOldOzonOrders(): void
    {
        $twoDaysOldOzonOrders = $this->ozonProcessingService->getOzonTwoDaysOrders();
        if(!$twoDaysOldOzonOrders->isEmpty())
        {
            foreach ($twoDaysOldOzonOrders as $order)
            {
                $postingId = $order->ozon_posting_id;
                $ozonApiInfo = json_decode($this->api->getPostings($postingId), true);
                $order = $ozonApiInfo['result'];
                $this->ozonProcessingService->updateOzonOrder([$order]);
            }
        }
    }
}
