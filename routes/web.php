<?php


Route::get('/chat', 'ChatController@index');

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
