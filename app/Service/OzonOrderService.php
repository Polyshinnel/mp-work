<?php

namespace App\Service;

use App\Http\Controllers\Utils\TimeController;
use App\Repostory\Ozon\OzonRepository;

class OzonOrderService
{
    private OzonRepository $ozonRepository;
    private TimeController $timeController;
    public function __construct(OzonRepository $ozonRepository, TimeController $timeController)
    {
        $this->ozonRepository = $ozonRepository;
        $this->timeController = $timeController;
    }

    public function getOzonOrderList(int $ozonStatusId): array
    {
        $result = [];
        $orders = $this->ozonRepository->getOzonOrderListByStatus($ozonStatusId);
        if(!$orders->isEmpty()){
            foreach ($orders as $order) {
                $labelInfo = $order->siteLabel;
                $siteStatus = $order->siteStatus;
                $ozonWarehouse = $order->ozonWarehouse;
                $site = $order->siteInfo;
                $ozonStatus = $order->ozonStatus;

                $date = $this->timeController->reformatDateTime($order->date_order_create);
                $siteLink = $site->host.'panel/?module=OrderAdmin&id='.$order->site_order_id;
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
}
