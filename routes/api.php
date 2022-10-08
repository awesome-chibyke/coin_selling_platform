<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\PaymentController;

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

//https://dataseller.io/api/confirm-transfer
Route::post('/confirm-transfer', [PaymentController::class, 'confirmFlutterWaveTransfer'])->name('confirm-transfer');
//https://dataseller.io/api/confirm-payment
Route::post('/confirm-payment', [PaymentController::class, 'confirmNowPaymentTansaction'])->name('confirm-payment');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});