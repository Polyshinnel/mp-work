<?php

namespace App\Http\Controllers\Settings\CommonSettings;

use App\Http\Controllers\BasePageController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LabelAddPageController extends BasePageController
{
    public function __invoke(Request $request)
    {
        $pageInfo = $this->getBasePageInfo($request);

        return view(
            'pages.Settings.Common.settings-common-labels-add',
            [
                'pageInfo' => $pageInfo,
                'link' => '/settings',
            ]
        );
    }
}
