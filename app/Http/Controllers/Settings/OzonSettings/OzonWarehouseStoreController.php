<?php

namespace App\Http\Controllers\Settings\OzonSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Ozon\OzonWarehouseSettings;
use Illuminate\Http\Request;

class OzonWarehouseStoreController extends Controller
{
    public function __invoke(OzonWarehouseSettings $request)
    {
        $data = $request->validated();
    }
}
