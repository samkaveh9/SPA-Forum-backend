<?php

use Illuminate\Support\Facades\Route;


Route::group(['namespace' => 'API\V01\Auth','prefix' => 'v1/auth'],function() {
   Route::post('/register', 'AuthController@register')->name('auth.register');
   Route::post('/login', 'AuthController@login')->name('auth.login');
   Route::post('/user', 'AuthController@user')->name('auth.user');
   Route::post('/logout', 'AuthController@logout')->name('auth.logout');
});