<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\BasePageController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repostory\CommonSettings\CommonSettingsRepository;
use App\Repostory\OzonSettings\OzonSettingsRepository;
use App\Service\CommonSettingsService;
use App\Service\OzonOrderService;
use App\Service\OzonSettingsService;
use Illuminate\Http\Request;

class OzonPageController extends BasePageController
{
    private OzonOrderService $ozonOrderService;
    private CommonSettingsRepository $commonSettingsRepository;
    private OzonSettingsRepository $ozonSettingsRepository;

    public function __construct(
        OzonOrderService $ozonOrderService,
        CommonSettingsRepository $commonSettingsRepository,
        OzonSettingsRepository $ozonSettingsRepository
    )
    {
        $this->ozonOrderService = $ozonOrderService;
        $this->commonSettingsRepository = $commonSettingsRepository;
        $this->ozonSettingsRepository = $ozonSettingsRepository;
    }

    public function __invoke(Request $request)
    {
        $pageInfo = $this->getBasePageInfo($request);
        $pageUrl = $request->path();
        $tabList = $this->getActiveTabByPageUrl($pageUrl);

        $orderStatus = $this->getStatusByPageUrl($pageUrl);
        $filters = $this->getFiltersOptions();
        $ozonOrders = [];

        if($orderStatus != 0) {
            $ozonOrders = $this->ozonOrderService->getOzonOrderList($orderStatus);
        }


        return view(
            'pages.Ozon.ozon',
            [
                'pageInfo' => $pageInfo,
                'link' => '/ozon',
                'order_info' => $ozonOrders,
                'tabList' => $tabList,
                'filters' => $filters
            ]
        );
    }

    private function getStatusByPageUrl(string $pageUrl): int
    {
        $ozonStatus = 1;

        if($pageUrl == 'ozon-list/awaiting-delivery')
        {
            $ozonStatus = 2;
        }

        if($pageUrl == 'ozon-list/delivery')
        {
            $ozonStatus = 3;
        }
        if($pageUrl == 'ozon-list/arbitration')
        {
            $ozonStatus = 4;
        }
        if($pageUrl == 'ozon-list/delivered')
        {
            $ozonStatus = 6;
        }
        if($pageUrl == 'ozon-list/canceled')
        {
            $ozonStatus = 5;
        }
        if($pageUrl == 'ozon-list/all')
        {
            $ozonStatus = 0;
        }

        return $ozonStatus;
    }

    private function getActiveTabByPageUrl(string $pageUrl): array
    {
        $pageUrl = '/'.$pageUrl;
        $tabList = [
            [
                'url' => '/ozon-list',
                'name' => 'Ожидают сборки',
                'active' => false
            ],
            [
                'url' => '/ozon-list/awaiting-delivery',
                'name' => 'Ожидают отгрузки',
                'active' => false
            ],
            [
                'url' => '/ozon-list/delivery',
                'name' => 'Доставляются',
                'active' => false
            ],
            [
                'url' => '/ozon-list/arbitration',
                'name' => 'Спорные',
                'active' => false
            ],
            [
                'url' => '/ozon-list/delivered',
                'name' => 'Доставлены',
                'active' => false
            ],
            [
                'url' => '/ozon-list/canceled',
                'name' => 'Отменены',
                'active' => false
            ],
            [
                'url' => '/ozon-list/all',
                'name' => 'Все',
                'active' => false
            ],
        ];

        $reformatTabList = [];
        foreach ($tabList as $tab) {
            if($tab['url'] == $pageUrl) {
                $tab['active'] = true;
            }
            $reformatTabList[] = $tab;
        }

        return $reformatTabList;
    }

    private function getFiltersOptions(): array
    {
        $siteStatusList = $this->commonSettingsRepository->getSiteStatusList();
        $siteLabelList = $this->commonSettingsRepository->getSiteLabels();
        $warehouseList = $this->ozonSettingsRepository->getOzonWarehouseList();

        $filters = [];

        if(!$siteStatusList->isEmpty()){
            foreach ($siteStatusList as $siteStatus) {
                $filters['site_status'][] = [
                    'id' => $siteStatus->id,
                    'name' => $siteStatus->name
                ];
            }
        }

        if(!$siteLabelList->isEmpty()){
            foreach ($siteLabelList as $siteLabel) {
                $filters['site_label'][] = [
                    'id' => $siteLabel->id,
                    'name' => $siteLabel->name
                ];
            }
        }

        if(!$warehouseList->isEmpty()){
            foreach ($warehouseList as $warehouse) {
                $filters['warehouse'][] = [
                    'id' => $warehouse->id,
                    'name' => $warehouse->name
                ];
            }
        }

        return $filters;
    }
}
