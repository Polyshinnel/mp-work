<?php

namespace App\Http\Controllers\Settings\OzonSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Ozon\OzonStatusSettings;
use App\Service\OzonSettingsService;
use Illuminate\Http\Request;

class OzonStatusStoreController extends Controller
{
    private OzonSettingsService $ozonSettingsService;

    public function __construct(OzonSettingsService $ozonSettingsService)
    {
        $this->ozonSettingsService = $ozonSettingsService;
    }

    public function __invoke(OzonStatusSettings $request)
    {
        $result = $this->ozonSettingsService->createOzonStatus($request);
        if($result['err'] != 'none') {
            $path = '/settings/ozon-settings/statuses/add?hasErr='.$result['err'];
            return response()->redirectTo($path);
        }
        return response()->redirectTo('/settings/ozon-settings/statuses');
    }
}
