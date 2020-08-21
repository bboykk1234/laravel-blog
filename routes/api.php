<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('guest')->group(function () {
    Route::prefix('auth')->group(function() {
        Route::post('register', 'Auth\RegisterController@create');
    });

    Route::middleware('email.verify')->prefix('email')->group(function() {
        Route::get('verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
        Route::post('resend', 'Auth\VerificationController@resend')->name('verification.resend');
    });
});
