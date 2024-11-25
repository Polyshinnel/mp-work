<?php

namespace App\Http\Controllers\Settings\CommonSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Common\OrderStatusSettings;
use Illuminate\Http\Request;

class SiteStatusStoreController extends Controller
{
    public function __invoke(OrderStatusSettings $request)
    {
        $data = $request->validated();
    }
}
