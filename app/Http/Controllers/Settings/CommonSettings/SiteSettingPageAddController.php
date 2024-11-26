<?php

namespace App\Http\Controllers\Settings\CommonSettings;

use App\Http\Controllers\BasePageController;
use App\Http\Controllers\Controller;
use App\Repostory\CommonSettings\CommonSettingsRepository;
use Illuminate\Http\Request;

class SiteSettingPageAddController extends BasePageController
{


    public function __invoke(Request $request)
    {
        $pageInfo = $this->getBasePageInfo($request);
        return view(
            'pages.Settings.Common.settings-common-site-add',
            [
                'pageInfo' => $pageInfo,
                'link' => '/settings',
            ]
        );
    }
}
