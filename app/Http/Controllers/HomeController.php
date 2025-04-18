<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Utils\TimeController;
use App\Models\Payment;
use App\Models\Returning;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends BasePageController
{
    public function __invoke(Request $request)
    {
        $pageInfo = $this->getBasePageInfo($request);


        return view(
            'home',
            [
                'pageInfo' => $pageInfo,
                'link' => '/',
            ]
        );
    }
}
