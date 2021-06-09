<?php

use Illuminate\Support\Facades\Route;


Route::group(['namespace' => 'API'], function () {
   // Authentication Routes
   Route::group(['namespace' => 'Auth','prefix' => 'auth'], function () {
      Route::post('/register', 'AuthController@register')->name('auth.register');
      Route::post('/login', 'AuthController@login')->name('auth.login');
      Route::get('/user', 'AuthController@user')->name('auth.user');
      Route::post('/logout', 'AuthController@logout')->name('auth.logout');
   });

   // Channel Routes
   Route::group(['namespace' => 'Channel','prefix' => 'channel'], function () {
      Route::get('/all', 'ChannelController@getAllChannelsList')->name('channel.all');
      Route::post('/create', 'ChannelController@createNewChannel')->name('channel.create');
      Route::put('/update', 'ChannelController@updateChannel')->name('channel.update');
      Route::delete('/delete', 'ChannelController@deleteChannel')->name('channel.delete');
   });

   // Thread Routes
   Route::group(['namespace' => 'Thread','prefix' => 'channel'], function () {
      Route::apiResource('/threads', 'ThreadController');
   });

   // Answer Routes
   Route::group(['namespace' => 'Thread','prefix' => 'answer'], function () {
      Route::apiResource('/answers', 'AnswerController');
   });

});
