<?php

namespace App\Http\Controllers\Settings\CommonSettings;

use App\Http\Controllers\BasePageController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repostory\CommonSettings\CommonSettingsRepository;
use Illuminate\Http\Request;

class SiteStatusPageController extends BasePageController
{
    private CommonSettingsRepository $commonSettingsRepository;

    public function __construct(CommonSettingsRepository $commonSettingsRepository)
    {
        $this->commonSettingsRepository = $commonSettingsRepository;
    }

    public function __invoke(Request $request)
    {
        $pageInfo = $this->getBasePageInfo($request);
        $siteStatusList = $this->commonSettingsRepository->getSiteStatusList();

        return view(
            'pages.Settings.Common.settings-common-statuses',
            [
                'pageInfo' => $pageInfo,
                'link' => '/settings',
                'siteStatusList' => $siteStatusList
            ]
        );
    }
}
