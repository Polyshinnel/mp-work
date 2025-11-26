<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\Controller;
use App\Service\OzonOrderService;
use Illuminate\Http\Request;

class MarkAsSendOzonController extends Controller
{
    private OzonOrderService $ozonOrderService;

    public function __construct(OzonOrderService $ozonOrderService)
    {
        $this->ozonOrderService = $ozonOrderService;
    }

    public function __invoke(Request $request)
    {
        $orders = $request->query('orders');
        $ordersMessages = [];
        foreach ($orders as $order) {
            $ordersMessages[] = $this->ozonOrderService->markOzonOrderAsSent($order);
        }
        return response()->json($ordersMessages);
    }
}
