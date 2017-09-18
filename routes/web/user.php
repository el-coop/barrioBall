<?php

Route::group(['prefix' => 'user'],function(){
	Route::get('/', 'UserController@edit');
	Route::delete('/', 'UserController@destroy');
	Route::get('/matches', 'UserController@getMatches');
	Route::patch('/username','UserController@updateUsername');
    Route::patch('/email','UserController@updateEmail');
    Route::patch('/password','UserController@updatePassword');
    Route::patch('/language','UserController@updateLanguage');
});
