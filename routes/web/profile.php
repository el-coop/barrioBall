<?php

Route::group(['prefix' => 'profile', 'middleware' => ['auth']],function(){
	Route::get('/', 'UserController@show');
	Route::delete('/', 'UserController@deleteUser');
	Route::get('/matches', 'UserController@getMatches');
	Route::patch('/username','UserController@updateUsername');
    Route::patch('/email','UserController@updateEmail');
    Route::patch('/password','UserController@updatePassword');
    Route::patch('/language','UserController@updateLanguage');
});
