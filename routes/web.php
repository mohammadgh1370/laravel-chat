<?php


//Route::get('/chat', 'ChatController@index');

Auth::routes();

Route::get('/', 'ChatController@index')->name('home');
