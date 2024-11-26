<?php

namespace App\Http\Controllers\Settings\CommonSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Common\SiteSettingsRequest;
use App\Service\CommonSettingsService;
use Illuminate\Http\Request;

class SiteStoreController extends Controller
{
    private CommonSettingsService $commonSettingsService;

    public function __construct(CommonSettingsService $commonSettingsService)
    {
        $this->commonSettingsService = $commonSettingsService;
    }

    public function __invoke(SiteSettingsRequest $request)
    {
        $result = $this->commonSettingsService->createSiteSettings($request);
        if($result['err'] != 'none') {
            $path = '/settings/common/sites/add?hasErr='.$result['err'];
            return response()->redirectTo($path);
        }
        return response()->redirectTo('/settings/common/sites');
    }
}
