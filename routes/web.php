<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Ozon\GetOzonLabelPage;
use App\Http\Controllers\Ozon\OzonLabelController;
use App\Http\Controllers\Ozon\OzonPageController;
use App\Http\Controllers\Ozon\TestController;
use App\Http\Controllers\Payment\GetPayments;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Returning\GetReturning;
use App\Http\Controllers\Returning\ReturningController;
use App\Http\Controllers\Settings\CommonSettings\CommonSettingsPage;
use App\Http\Controllers\Settings\CommonSettings\LabelAddPageController;
use App\Http\Controllers\Settings\CommonSettings\LabelsPageController;
use App\Http\Controllers\Settings\CommonSettings\LabelsStoreController;
use App\Http\Controllers\Settings\CommonSettings\SiteSettingPageAddController;
use App\Http\Controllers\Settings\CommonSettings\SiteSettingPageController;
use App\Http\Controllers\Settings\CommonSettings\SiteStatusPageController;
use App\Http\Controllers\Settings\CommonSettings\SiteStatusStoreController;
use App\Http\Controllers\Settings\CommonSettings\SiteStoreController;
use App\Http\Controllers\Settings\CommonSettings\StatusAddPageController;
use App\Http\Controllers\Settings\CreateSettingsController;
use App\Http\Controllers\Settings\OzonSettings\OzonCommonSettingsPage;
use App\Http\Controllers\Settings\OzonSettings\OzonStatusAddPageController;
use App\Http\Controllers\Settings\OzonSettings\OzonStatusPageController;
use App\Http\Controllers\Settings\OzonSettings\OzonStatusStoreController;
use App\Http\Controllers\Settings\OzonSettings\OzonWarehousePageController;
use App\Http\Controllers\Settings\OzonSettings\OzonWarehouseStoreController;
use App\Http\Controllers\Settings\OzonSettings\WarehouseAddPageController;
use App\Http\Controllers\Settings\SettingsPageController;
use App\Http\Controllers\Settings\StoreSettingsController;
use App\Http\Middleware\LoggedUser;
use App\Jobs\AddNewOzonOrders;
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

Route::middleware([LoggedUser::class])->group(function () {
    Route::get('/ozon-list', OzonPageController::class);
    Route::get('/ozon-list/awaiting-delivery', OzonPageController::class);
    Route::get('/ozon-list/delivery', OzonPageController::class);
    Route::get('/ozon-list/arbitration', OzonPageController::class);
    Route::get('/ozon-list/delivered', OzonPageController::class);
    Route::get('/ozon-list/canceled', OzonPageController::class);
    Route::get('/ozon-list/all', OzonPageController::class);

    Route::get('/ozon/getLabels', GetOzonLabelPage::class);
});


Route::middleware([LoggedUser::class])->group(function () {
    Route::get('/settings', SettingsPageController::class);
    Route::get('/settings/common', CommonSettingsPage::class);

    Route::get('/settings/common/site-status', SiteStatusPageController::class);
    Route::get('/settings/common/site-status/add', StatusAddPageController::class);

    Route::get('/settings/common/marks', LabelsPageController::class);
    Route::get('/settings/common/marks/add', LabelAddPageController::class);

    Route::get('/settings/common/sites', SiteSettingPageController::class);
    Route::get('/settings/common/sites/add', SiteSettingPageAddController::class);

    Route::post('/settings/common/marks/add', LabelsStoreController::class);
    Route::post('/settings/common/site-status/add', SiteStatusStoreController::class);
    Route::post('/settings/common/sites/add', SiteStoreController::class);


    Route::get('/settings/ozon-settings', OzonCommonSettingsPage::class);
    Route::get('/settings/ozon-settings/statuses', OzonStatusPageController::class);
    Route::get('/settings/ozon-settings/statuses/add', OzonStatusAddPageController::class);
    Route::get('/settings/ozon-settings/warehouses', OzonWarehousePageController::class);
    Route::get('/settings/ozon-settings/warehouses/add', WarehouseAddPageController::class);

    Route::post('/settings/ozon-settings/statuses/add', OzonStatusStoreController::class);
    Route::post('/settings/ozon-settings/warehouses/add', OzonWarehouseStoreController::class);
});

Route::middleware([LoggedUser::class])->group(function () {
    Route::post('/ozon-list/test', TestController::class);
    Route::get('/ozon-list/test-api', [AddNewOzonOrders::class, 'getSimplaOrderProcessing']);
});
