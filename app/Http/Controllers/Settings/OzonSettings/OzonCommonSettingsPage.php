<?php

namespace App\Http\Controllers\Settings\OzonSettings;

use App\Http\Controllers\BasePageController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OzonCommonSettingsPage extends BasePageController
{
    public function __invoke(Request $request)
    {
        $pageInfo = $this->getBasePageInfo($request);


        return view(
            'pages.Settings.Ozon.settings-ozon',
            [
                'pageInfo' => $pageInfo,
                'link' => '/settings',
            ]
        );
    }
}
