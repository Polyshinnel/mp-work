<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\BasePageController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OzonPageController extends BasePageController
{
    public function __invoke(Request $request)
    {
        $pageInfo = $this->getBasePageInfo($request);


        return view(
            'pages.Ozon.ozon',
            [
                'pageInfo' => $pageInfo,
                'link' => '/ozon',
            ]
        );
    }
}
