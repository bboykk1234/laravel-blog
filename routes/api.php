<?php

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

Route::middleware('auth:api')->group(function() {
    Route::middleware('can:isAdmin')->group(function() {
        Route::prefix('users')->group(function() {
            Route::get('/', 'UserController@index');
        });

        Route::prefix('articles')->group(function() {
            Route::delete('{id}', 'ArticleController@destroy');
            Route::post('/', 'ArticleController@store');
            Route::put('{id}', 'ArticleController@update');
        });

        Route::prefix('blog')->group(function() {
            Route::get('/statistics', 'StatisticController@index');
        });
    });

    Route::prefix('comments')->group(function() {
        Route::delete('{id}', 'CommentController@destroy');
        Route::post('/', 'CommentController@store');
        Route::put('{id}', 'CommentController@update');
    });
});

Route::post('register', 'Auth\RegisterController@create');
Route::prefix('articles')->group(function() {
    Route::get('/', 'ArticleController@index');
    Route::get('{id}', 'ArticleController@show');
});

Route::middleware('auth:email')->prefix('email')->group(function() {
    Route::get('verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('resend', 'Auth\VerificationController@resend')->name('verification.resend');
});
