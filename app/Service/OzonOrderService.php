<?php

namespace App\Service;

use App\Http\Controllers\Utils\TimeController;
use App\Repostory\Ozon\OzonRepository;
use Illuminate\Database\Eloquent\Collection;

class OzonOrderService
{
    private OzonRepository $ozonRepository;
    private TimeController $timeController;
    public function __construct(OzonRepository $ozonRepository, TimeController $timeController)
    {
        $this->ozonRepository = $ozonRepository;
        $this->timeController = $timeController;
    }

    public function getOzonFilteredOrder(int $statusId, array $queryFilter): ?Collection
    {
        $filter = [];

        if($statusId != 0) {
            $filter[] = [
                'ozon_status_id', '=', $statusId
            ];
        }
        if(isset($queryFilter['site_status']))
        {
            $filter[] = [
                'site_status_id', '=', $queryFilter['site_status']
            ];
        }
        if(isset($queryFilter['label']))
        {
            $filter[] = [
                'site_label_id', '=', $queryFilter['label']
            ];
        }
        if(isset($queryFilter['warehouse']))
        {
            $filter[] = [
                'ozon_warehouse_id', '=', $queryFilter['warehouse']
            ];
        }
        if($filter){
            return $ozonOrder = $this->ozonRepository->getFilteredOzonOrders($filter);
        } else {
            return $this->ozonRepository->getAllOrders();
        }
    }

    public function getOzonOrderList(Collection $orders): array
    {
        $result = [];
        if(!$orders->isEmpty()){
            foreach ($orders as $order) {
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

                $hasBtn = false;

                if($order->ozon_status_id == '2')
                {
                    $hasBtn = true;
                }

                $result[] = [
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
            }
        }
        return $result;
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
}
