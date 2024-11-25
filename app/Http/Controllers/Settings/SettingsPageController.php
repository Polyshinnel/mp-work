<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\BasePageController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SettingsPageController extends BasePageController
{
    public function __invoke(Request $request)
    {
        $pageInfo = $this->getBasePageInfo($request);


        return view(
            'pages.Settings.settings',
            [
                'pageInfo' => $pageInfo,
                'link' => '/',
            ]
        );
    }
}
