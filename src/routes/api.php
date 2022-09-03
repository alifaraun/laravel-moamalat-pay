<?php

use Illuminate\Support\Facades\Route;
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

        Route::post(config('moamalat-pay.notification.url'), [NotificationController::class, 'store'])->middleware('moamalat-allowed-ips');
    });
