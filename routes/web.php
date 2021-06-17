<?php

use Illuminate\Support\Facades\Route;
use App\Infrastructure\Framework\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/product/{productId}', function (int $productId) {
    return view('product', compact('productId'));
});

Route::get('/order/{orderId}', function (int $orderId) {
    return view('order', compact('orderId'));
});

Route::get('login', '\App\Infrastructure\Framework\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::post('login', '\App\Infrastructure\Framework\Http\Controllers\Auth\LoginController@login');
Route::post('logout', '\App\Infrastructure\Framework\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::get('register', '\App\Infrastructure\Framework\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', '\App\Infrastructure\Framework\Http\Controllers\Auth\RegisterController@register');
Route::resetPassword();
Route::emailVerification();

Route::get('/home', [HomeController::class, 'index'])->name('home');
