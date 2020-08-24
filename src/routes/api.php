<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', 'Api\PaymentController@register')->name('api.payments.register');
Route::post('/payments/period', 'Api\PaymentController@paymentPeriod')->name('api.payments.getPaymentPeriod');


Route::post('/fake-success', static function (Request $request) {
    Log::info('fake', $request->all());
    return response(true);
})->name('api.payments.getPaymentPeriod');
