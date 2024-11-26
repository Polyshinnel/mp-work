<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\BasePageController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Service\OzonOrderService;
use Illuminate\Http\Request;

class OzonPageController extends BasePageController
{
    private OzonOrderService $ozonOrderService;

    public function __construct(OzonOrderService $ozonOrderService)
    {
        $this->ozonOrderService = $ozonOrderService;
    }

    public function __invoke(Request $request)
    {
        $pageInfo = $this->getBasePageInfo($request);
        $ozonOrders = $this->ozonOrderService->getOzonOrderList(1);

        return view(
            'pages.Ozon.ozon',
            [
                'pageInfo' => $pageInfo,
                'link' => '/ozon',
                'order_info' => $ozonOrders
            ]
        );
    }
}
