<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Payment\GetPayments;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Returning\GetReturning;
use App\Http\Controllers\Returning\ReturningController;
use App\Http\Controllers\Settings\CreateSettingsController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Settings\StoreSettingsController;
use App\Http\Middleware\LoggedUser;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/auth', LoginController::class);
Route::post('/auth', AuthController::class);

Route::middleware([LoggedUser::class])->group(function () {
    Route::get('/', HomeController::class);
});
