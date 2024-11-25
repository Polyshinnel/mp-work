<?php

namespace App\Http\Controllers\Settings\CommonSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Common\OrderStatusSettings;
use App\Service\CommonSettingsService;
use Illuminate\Http\Request;

class SiteStatusStoreController extends Controller
{
    private CommonSettingsService $commonSettingsService;

    public function __construct(CommonSettingsService $commonSettingsService)
    {
        $this->commonSettingsService = $commonSettingsService;
    }

    public function __invoke(OrderStatusSettings $request)
    {
        $result = $this->commonSettingsService->createSiteStatus($request);
        if($result['err'] != 'none') {
            $path = '/settings/common/site-status/add?hasErr='.$result['err'];
            return response()->redirectTo($path);
        }
        return response()->redirectTo('/settings/common/site-status');
    }
}
