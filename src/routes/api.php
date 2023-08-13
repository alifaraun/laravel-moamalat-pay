<?php

use Illuminate\Support\Facades\Route;
use MoamalatPay\Http\Controllers\ConfigController;
use MoamalatPay\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')
    ->prefix('api')
    ->group(function () {

        Route::post(config('moamalat-pay.notification.url'), [NotificationController::class, 'store'])
            ->middleware('moamalat-allowed-ips')
            ->name(config('moamalat-pay.notification.route_name'));

        Route::get(config('moamalat-pay.generate-securekey.url'), [ConfigController::class, 'generateSecureKey'])
            ->name(config('moamalat-pay.generate-securekey.route_name'));
    });
