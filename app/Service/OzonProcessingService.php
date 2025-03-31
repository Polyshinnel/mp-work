<?php

namespace App\Service;

use App\Http\Controllers\Ozon\OzonApi;
use App\Models\OzonOrder;
use App\Repostory\CommonSettings\CommonSettingsRepository;
use App\Repostory\Ozon\OzonRepository;
use App\Repostory\OzonSettings\OzonSettingsRepository;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OzonProcessingService
{
    private OzonRepository $ozonRepository;
    private OzonSettingsRepository $ozonSettingsRepository;
    private CommonSettingsService $commonSettingsService;
    private OzonSettingsService $ozonSettingsService;

    public function __construct(
        OzonRepository $ozonRepository,
        OzonSettingsRepository $ozonSettingsRepository,
        CommonSettingsService $commonSettingsService,
        OzonSettingsService $ozonSettingsService
    )
    {
        $this->ozonRepository = $ozonRepository;
        $this->ozonSettingsRepository = $ozonSettingsRepository;
        $this->commonSettingsService = $commonSettingsService;
        $this->ozonSettingsService = $ozonSettingsService;
    }

    public function getOzonPackingsByPeriod(OzonApi $api, array $warehouses, array $statuses, string $dateStart, string $dateEnd): array
    {
        $orderList = $this->getRawOzonData($api, $warehouses, $statuses, $dateStart, $dateEnd);
        return $this->processingOzonOrders($orderList);
    }

    public function processingPostingsToDb(array $simplaOrders): array
    {
        date_default_timezone_set('Europe/Moscow');
        $processedResult = [];
        $commonSettings = $this->commonSettingsService->getCommonSettingsAssociativeData();
        $ozonSettings = $this->ozonSettingsService->getOzonSettingsAssociativeData();

        if($simplaOrders && $commonSettings && $ozonSettings)
        {
            foreach ($simplaOrders as $order) {
                $err = [];
                $warehouseId = '';
                $ozonStatusId = '';
                $siteStatusId = '';
                $siteLabelId = '';
                $siteId = '';

                $dateSec = strtotime($order['date_create']) + 1080;
                $dateCreate = date('Y-m-d H:i:s', $dateSec);


                if(isset($ozonSettings['warehouses'][$order['ozon_warehouse_id']])) {
                    $warehouseId = $ozonSettings['warehouses'][$order['ozon_warehouse_id']];
                } else {
                    $err[$order['ozon_posting_id']][] = sprintf('Не могу найти склад: %s', $order['ozon_warehouse_id']);
                }
                if(isset($ozonSettings['ozon_status'][$order['ozon_status']])) {
                    $ozonStatusId = $ozonSettings['ozon_status'][$order['ozon_status']];
                }else {
                    $err[$order['ozon_posting_id']][] = sprintf('Не могу найти статус: %s', $order['ozon_status']);
                }
                if(isset($commonSettings['sites'][$order['db_name']]))
                {
                    $siteId = $commonSettings['sites'][$order['db_name']];
                }else {
                    $err[$order['ozon_posting_id']][] = sprintf('Не могу найти сайт: %s', $order['db_name']);
                }
                if(isset($commonSettings['labels'][$order['site_label_id']])){
                    $siteLabelId = $commonSettings['labels'][$order['site_label_id']];
                }else {
                    $err[$order['ozon_posting_id']][] = sprintf('Не могу найти метку: %s', $order['site_label_id']);
                }
                if(isset($commonSettings['site_status'][$order['site_status_id']])){
                    $siteStatusId = $commonSettings['site_status'][$order['site_status_id']];
                }else {
                    $err[$order['ozon_posting_id']][] = sprintf('Не могу найти статус сайта: %s', $order['site_status_id']);
                }

                if(empty($err)){
                    $processedResult[$order['ozon_posting_id']] = [
                        'site_status_id' => $siteStatusId,
                        'site_label_id' => $siteLabelId,
                        'ozon_status_id' => $ozonStatusId,
                        'ozon_warehouse_id' => $warehouseId,
                        'site_id' => $siteId,
                        'ozon_order_id' => $order['ozon_order_id'],
                        'ozon_posting_id' => $order['ozon_posting_id'],
                        'products_count' => $order['product_count'],
                        'site_order_id' => $order['site_order_id'],
                        'date_order_create' => $dateCreate
                    ];
                } else {
                    print_r($err);
                }
            }
        }

        return $processedResult;
    }

    public function createOzonOrders(array $orders): void
    {
        if($orders)
        {
            foreach ($orders as $order)
            {
                try {
                    DB::beginTransaction();
                    $this->ozonRepository->createOzonOrder($order);
                    DB::commit();
                } catch (\Exception $exception)
                {
                    DB::rollback();
                    echo $exception->getMessage();
                    die();
                }
            }
        }
    }

    private function getRawOzonData(OzonApi $api, array $warehouses, array $statuses, string $dateStart, string $dateEnd): array
    {
        $rawResults = [];

        if($warehouses && $statuses){
            foreach($warehouses as $warehouse){
                foreach($statuses as $status){
                    $result = $api->getFilteredPostings($dateStart, $dateEnd, [$warehouse], $status);
                    if($result){
                        $result = json_decode($result, true);

                        if($result['result']['postings']) {
                            foreach($result['result']['postings'] as $posting){
                                $checkOzonPostingInfo = $this->getOzonOrderByPosting($posting['posting_number']);
                                if(!$checkOzonPostingInfo){
                                    $rawResults[$warehouse][$status][] = $posting;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $rawResults;
    }

    private function processingOzonOrders(array $orderList): array
    {
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
        return $processedOrders;
    }

    private function getOzonOrderByPosting($ozonPostingId): ?OzonOrder
    {
        $filter[] = ['ozon_posting_id', '=', $ozonPostingId];
        return $this->ozonRepository->getFilteredOzonPacking($filter);
    }

    public function getOzonTwoDaysOrders(): ?Collection
    {
        $statusList = $this->ozonSettingsService->getOzonWatchableStatusList();
        $ozonList = $this->ozonRepository->getOzonWatchableOrderList($statusList);
        $filter = [
            ['date_order_create', '<', now()->subDays(2)]
        ];
        return OzonOrder::where($filter)->whereIn('ozon_status_id', $statusList)->get();
    }

    public function getOzonWatchableOrders(): ?Collection
    {
        $statusList = $this->ozonSettingsService->getOzonWatchableStatusList();
        return $this->ozonRepository->getOzonWatchableOrderList($statusList);
    }

    public function getSiteWatchableOrders(): ?Collection
    {
        $statusList = $this->commonSettingsService->getSiteStatusWatchableList();
        return $this->ozonRepository->getSiteStatusWatchableList($statusList);
    }

    public function updateOzonOrder(array $ozonOrders): void
    {
        foreach ($ozonOrders as $order) {
            $ozonStatus = $this->ozonSettingsRepository->getOzonStatusByName($order['status']);
            if($ozonStatus)
            {
                $updateArr = [
                    'ozon_status_id' => $ozonStatus->id
                ];
                $ozonPosting = $this->ozonRepository->getOzonOrderByPosting($order['posting_number']);
                if($ozonPosting){
                    if($ozonPosting->ozon_status_id != $ozonStatus->id)
                    {
                        $this->ozonRepository->updateOzonOrderByPostingId($order['posting_number'], $updateArr);
                    }
                }
            }
        }
    }

    public function updateOzonOrderSite(int $orderId, array $siteInfo, array $commonSettings): void
    {
        $simplaSiteStatusId = $siteInfo['status'];
        $simplaSiteLabelId = $siteInfo['labels']['label_id'];
        $siteStatusId = $commonSettings['site_status'][$simplaSiteStatusId];
        $siteLabelId = $commonSettings['labels'][$simplaSiteLabelId];
        $updateArr = [
            'site_status_id' => $siteStatusId,
            'site_label_id' => $siteLabelId,
        ];

        try {
            $this->ozonRepository->updateOzonOrder($updateArr, $orderId);
        } catch (\Exception $exception)
        {
            dd($exception->getMessage());
        }

    }

    public function updateOzonOrderSiteBySiteInfo(string $siteOrderId, array $siteInfo, array $commonSettings): void
    {
        $simplaSiteStatusId = $siteInfo['status'];
        $siteStatusId = $commonSettings['site_status'][$simplaSiteStatusId];
        $updateArr = [
            'site_status_id' => $siteStatusId,
        ];

        if(isset($siteInfo['labels']['label_id']))
        {
            $simplaSiteLabelId = $siteInfo['labels']['label_id'];
            $siteLabelId = $commonSettings['labels'][$simplaSiteLabelId];
            $updateArr = [
                'site_status_id' => $siteStatusId,
                'site_label_id' => $siteLabelId,
            ];

        }

        try {
            $this->ozonRepository->updateOzonOrderByOzonPostingId($updateArr, $siteOrderId);
        } catch (\Exception $exception)
        {
            dd($exception->getMessage());
        }

    }
}
