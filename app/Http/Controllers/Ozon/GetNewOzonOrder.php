<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\Controller;
use App\Jobs\AddNewOzonOrders;
use Illuminate\Http\Request;

class GetNewOzonOrder extends Controller
{
    private AddNewOzonOrders $orderController;

    public function __construct(AddNewOzonOrders $orderController)
    {
        $this->orderController = $orderController;
    }

    public function __invoke()
    {
        $this->orderController->addOrderToOrderList();
        return response()->json(['status' => 'ok']);
    }
}
