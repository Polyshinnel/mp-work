<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OzonPageController extends Controller
{
    public function __invoke()
    {
        date_default_timezone_set('Europe/Moscow');
        $user = User::find(session('user_id'));
        $pageTitle = 'Ozon';
        $breadcrumbs = [
            [
                'name' => 'Ozon',
                'link' => '/ozon',
                'active' => true
            ],
        ];


        return view(
            'Ozon.ozon',
            [
                'username' => $user->name,
                'page_title' => $pageTitle,
                'breadcrumbs' => $breadcrumbs,
                'block_title' => 'Озон',
                'link' => '/ozon',
            ]
        );
    }
}
