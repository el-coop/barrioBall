<?php

Route::group(['prefix' => 'user'],function(){
	Route::get('/', 'UserController@edit');
	Route::delete('/', 'UserController@destroy');
	Route::get('/matches', 'UserController@getMatches');
	Route::patch('/user/username','UserController@updateUsername');
    Route::patch('/user/email','UserController@updateEmail');
    Route::patch('/user/password','UserController@updatePassword');
    Route::patch('/user/language','UserController@updateLanguage');
});
