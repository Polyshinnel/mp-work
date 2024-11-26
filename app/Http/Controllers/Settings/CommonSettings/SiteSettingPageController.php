<?php

namespace App\Http\Controllers\Settings\CommonSettings;

use App\Http\Controllers\BasePageController;
use App\Http\Controllers\Controller;
use App\Repostory\CommonSettings\CommonSettingsRepository;
use Illuminate\Http\Request;

class SiteSettingPageController extends BasePageController
{
    private CommonSettingsRepository $commonSettingsRepository;

    public function __construct(CommonSettingsRepository $commonSettingsRepository)
    {
        $this->commonSettingsRepository = $commonSettingsRepository;
    }

    public function __invoke(Request $request)
    {
        $pageInfo = $this->getBasePageInfo($request);
        $siteList = $this->commonSettingsRepository->getSitesSettings();

        return view(
            'pages.Settings.Common.settings-common-site',
            [
                'pageInfo' => $pageInfo,
                'link' => '/settings',
                'siteList' => $siteList
            ]
        );
    }
}
