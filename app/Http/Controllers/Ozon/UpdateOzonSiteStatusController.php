<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SimplaOrders\SimplaOrderController;
use App\Jobs\UpdateOzonOrders;
use App\Models\OzonOrder;
use App\Repostory\Ozon\OzonRepository;
use App\Service\CommonSettingsService;
use App\Service\OzonProcessingService;
use Illuminate\Http\Request;

class UpdateOzonSiteStatusController extends Controller
{
    private UpdateOzonOrders $orderJob;
    private OzonApi $api;
    private OzonProcessingService $processingService;
    private CommonSettingsService $commonSettingsService;
    private SimplaOrderController $simplaOrderController;

    public function __construct(
        UpdateOzonOrders $orderJob,
        OzonApi $api,
        OzonProcessingService $processingService,
        CommonSettingsService $commonSettingsService,
        SimplaOrderController $simplaOrderController,
        OzonRepository $ozonRepository
    )
    {
        $this->orderJob = $orderJob;
        $this->api = $api;
        $this->processingService = $processingService;
        $this->commonSettingsService = $commonSettingsService;
        $this->simplaOrderController = $simplaOrderController;
        $this->ozonRepository = $ozonRepository;
    }

    public function __invoke()
    {
        $this->orderJob->updateSiteStatusArr();
        return response()->json(['status' => 'ok']);
    }

    public function update(int $orderId)
    {
        $order = OzonOrder::find($orderId);
        $siteInfo = $order->siteInfo;
        $siteOrderInfo = [
            'id' => $order->site_order_id,
            'database' => $siteInfo->db_name
        ];
        $commonSettings = $this->commonSettingsService->getCommonSettingsAssociativeData();
        $simplaResult = $this->simplaOrderController->getInternalOrderList([$siteOrderInfo]);
        if($simplaResult)
        {
            $this->processingService->updateOzonOrderSiteBySiteInfo($simplaResult[0]['external_cs_id'], $simplaResult[0], $commonSettings);
        }

        $postingId = $order->ozon_posting_id;
        $ozonApiInfo = json_decode($this->api->getPostings($postingId), true);
        $ozonOrder = $ozonApiInfo['result'];
        $this->processingService->updateOzonOrder([$ozonOrder]);
        return back();
    }

    public function updateStatus(Request $request)
    {
        $orders = $request->query('orders');
        foreach ($orders as $order) {
            $ozonOrder = $this->ozonRepository->getOzonOrderById($order);
            if ($ozonOrder) {
                $siteInfo = $ozonOrder->siteInfo;
                $internalId = $ozonOrder->site_order_id;
                $currentStatus = $ozonOrder->siteStatus;
                if($currentStatus->watchable && $currentStatus->name != 'Резерв') {

                    $status = 9;
                    $database = $siteInfo->db_name;
                    $this->simplaOrderController->updateOrderStatus($internalId, $database, $status);
                    $this->update($order);
                }
            }
        }
        return back();
    }
}
