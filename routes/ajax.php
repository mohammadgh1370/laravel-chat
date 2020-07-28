<?php


Route::get('users', 'Ajax\UserController@index');
Route::post('messages', 'Ajax\MessageController@index');
Route::post('messages/send', 'Ajax\MessageController@store');