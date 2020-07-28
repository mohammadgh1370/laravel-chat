<?php

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

Route::group(['prefix' => 'V1'], function (){
    Route::post('/register', 'Api\V1\UserController@register');
    Route::post('/login', 'Api\V1\UserController@login');
    Route::post('/message', 'Api\V1\MessageController@store');
    Route::post('/messages', 'Api\V1\MessageController@index');
});

