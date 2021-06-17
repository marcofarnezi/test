<?php

use App\Infrastructure\Framework\Http\Controllers\Cart\AddCouponController;
use App\Infrastructure\Framework\Http\Controllers\Cart\AddProductController;
use App\Infrastructure\Framework\Http\Controllers\Cart\GetOrderInfoController;
use App\Infrastructure\Framework\Http\Controllers\Cart\PaymentController;
use App\Infrastructure\Framework\Http\Controllers\Cart\RemoveProductController;
use App\Infrastructure\Framework\Http\Controllers\Product\ProductDetailController;
use App\Infrastructure\Framework\Http\Controllers\User\SaveDetailController;
use App\Infrastructure\Framework\Http\Controllers\User\UserDetailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Infrastructure\Framework\Http\Controllers\Product\ProductsController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('order/{orderId}', GetOrderInfoController::class);
Route::get('products', ProductsController::class);
Route::get('product/{productId}', ProductDetailController::class);
Route::post('user/{userId?}', SaveDetailController::class);
Route::get('user/{userId}', UserDetailController::class);
Route::post('card/{orderId?}', AddProductController::class);
Route::delete('card/{orderId}/product/{productId}', RemoveProductController::class);
Route::put('payment/{orderId}/user/{userId}', PaymentController::class);
Route::put('coupon/{couponCode}/order/{orderId}', AddCouponController::class);
