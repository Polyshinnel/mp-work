<?php

namespace App\Http\Controllers\Settings\OzonSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Ozon\OzonWarehouseSettings;
use App\Service\OzonSettingsService;
use Illuminate\Http\Request;

class OzonWarehouseStoreController extends Controller
{
    private OzonSettingsService $ozonSettingsService;

    public function __construct(OzonSettingsService $ozonSettingsService)
    {
        $this->ozonSettingsService = $ozonSettingsService;
    }

    public function __invoke(OzonWarehouseSettings $request)
    {
        $result = $this->ozonSettingsService->createOzonWarehouse($request);
        if($result['err'] != 'none') {
            $path = '/settings/ozon-settings/warehouses/add?hasErr='.$result['err'];
            return response()->redirectTo($path);
        }
        return response()->redirectTo('/settings/ozon-settings/warehouses');
    }
}
