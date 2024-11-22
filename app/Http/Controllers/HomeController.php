<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Utils\TimeController;
use App\Models\Payment;
use App\Models\Returning;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        date_default_timezone_set('Europe/Moscow');
        $user = User::find(session('user_id'));
        $pageTitle = 'Дашборд';
        $breadcrumbs = [
            [
                'name' => 'Главная',
                'link' => '/',
                'active' => true
            ]
        ];


        return view(
            'home',
            [
                'username' => $user->name,
                'page_title' => $pageTitle,
                'breadcrumbs' => $breadcrumbs,
                'block_title' => 'Активность',
                'link' => '/',
            ]
        );
    }
}
