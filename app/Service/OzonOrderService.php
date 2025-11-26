<?php

namespace App\Service;

use App\Http\Controllers\Ozon\OzonApi;
use App\Http\Controllers\Utils\TimeController;
use App\Repostory\Ozon\OzonProductRepository;
use App\Repostory\Ozon\OzonRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OzonOrderService
{
    private OzonRepository $ozonRepository;
    private TimeController $timeController;
    private OzonApi $ozonApi;
    private OzonProductRepository $ozonProductRepository;
    private OzonProcessingService $processingService;

    public function __construct(
        OzonRepository $ozonRepository,
        TimeController $timeController,
        OzonApi $ozonApi,
        OzonProductRepository $ozonProductRepository,
        OzonProcessingService $processingService
    )
    {
        $this->ozonRepository = $ozonRepository;
        $this->timeController = $timeController;
        $this->ozonApi = $ozonApi;
        $this->ozonProductRepository = $ozonProductRepository;
        $this->processingService = $processingService;
    }

    public function getOzonFilteredOrder(int $statusId, array $queryFilter, int $perPage = 20): LengthAwarePaginator
    {
        $filter = [];

        if ($statusId !== 0) {
            $filter['ozon_status_id'] = $statusId;
        }

        if (isset($queryFilter['site_status'])) {
            $filter['site_status_id'] = $queryFilter['site_status'];
        }

        if (isset($queryFilter['label'])) {
            $filter['site_label_id'] = $queryFilter['label'];
        }

        if (isset($queryFilter['warehouse'])) {
            $filter['ozon_warehouse_id'] = $queryFilter['warehouse'];
        }

        $searchQuery = $queryFilter['search'] ?? null;

        return $this->ozonRepository->getPaginatedOrders($filter, $perPage, $searchQuery);
    }

    public function getOzonOrderList(LengthAwarePaginator $orders): LengthAwarePaginator
    {
        if($orders->count() === 0){
            return $orders;
        }

        $orders->setCollection(
            $orders->getCollection()->transform(function ($order) {
                $labelInfo = $order->siteLabel;
                $siteStatus = $order->siteStatus;
                $ozonWarehouse = $order->ozonWarehouse;
                $site = $order->siteInfo;
                $ozonStatus = $order->ozonStatus;

                $date = $this->timeController->reformatDateTime($order->date_order_create);
                $siteLink = $site->host.'panel?module=OrderAdmin&id='.$order->site_order_id;
                $siteOrderName = $site->prefix.'-'.$order->site_order_id;
                $ozonLink = sprintf(
                    'https://seller.ozon.ru/app/postings/fbs?tab=%s&show=postings&postingDetails=%s',
                    $ozonStatus->ozon_status_name,
                    $order->ozon_posting_id
                );

                $hasBtn = $order->ozon_status_id == '2';

                return [
                    'id' => $order->id,
                    'date' => $date,
                    'site_link' => $siteLink,
                    'site_order' => $siteOrderName,
                    'ozon_posting_id' => $order->ozon_posting_id,
                    'ozon_link' => $ozonLink,
                    'site_status_name' => $siteStatus->name,
                    'site_status_color' => $siteStatus->color,
                    'site_label_name' => $labelInfo->name,
                    'site_label_color' => $labelInfo->color,
                    'warehouse_name' => $ozonWarehouse->name,
                    'has_btn' => $hasBtn
                ];
            })
        );

        return $orders;
    }

    public function getOzonOrderListBlock($orders): array
    {
        $result = [];
        $count = 0;
        $totalCount = 0;
        $tempArr = [];
        foreach ($orders as $order)
        {
            $siteInfo = $order->siteInfo;
            $count++;
            $totalCount++;
            $tempArr[] = [
                'id' => $order->site_order_id,
                'database' => $siteInfo->db_name
            ];
            if($count > 40)
            {
                $result[] = $tempArr;
                $tempArr = [];
                $count = 0;
            }
            if($totalCount == count($orders)){
                $result[] = $tempArr;
            }
        }
        return $result;
    }

    public function markOzonOrderAsSent(int $orderId) {
        $order = $this->ozonRepository->getFilteredOzonOrders(['id' => $orderId]);
        if(!$order->isEmpty()) {
            $order = $order->first();
        } else {
            return [
                'error' => 'Order not found'
            ];
        }
        $postingId = $order->ozon_posting_id;
        $siteStatusId = $order->site_status_id;
        if($siteStatusId != 3) {
            return ['error' => "Заказ {$postingId} не в статусе Принят"];
        }
        $ozonProducts = $this->ozonProductRepository->getOzonOrderProduct($orderId);
        if($ozonProducts->count() > 1)
        {
            return ['error' => "Заказ {$postingId} содержит более одного товара"];
        }

        $ozonProduct = $ozonProducts->first();
        $quantity = $ozonProduct->quantity;
        if($quantity > 1) {
            return ['error' => "Заказ {$postingId} содержит более одного товара"];
        }

        $productInfo = $this->ozonProductRepository->getProductById($ozonProduct->product_id);


        $count = 5;
        for($i = 0; $i < $count; $i++) {
            $sendingResult = $this->ozonApi->orderMarkAsShipped($postingId, $productInfo->product_id, 1);
            $sendingResult = json_decode($sendingResult, true);
            if(!isset($sendingResult['result']))
            {
                return ['error' => 'Не удалось отправить заказ'. $postingId];
            }
            $checkSendingResult = $this->ozonApi->getPostings($postingId);
            $checkSendingResult = json_decode($checkSendingResult, true);
            if(isset($checkSendingResult['result']['substatus']))
            {

                if($checkSendingResult['result']['substatus'] != 'ship_failed') {
                    $ozonOrder = $checkSendingResult['result'];
                    $this->processingService->updateOzonOrder([$ozonOrder]);
                    return ['message' => 'Заказ '.$postingId.' отправлен'];
                }
            }
        }
        return ['error' => 'Не удалось отправить заказ'. $postingId];
    }
}
