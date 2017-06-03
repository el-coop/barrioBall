<?php

Route::group(['prefix' => 'user'],function(){
	Route::get('/', 'UserController@edit');
	Route::patch('/', 'UserController@update');
	Route::delete('/', 'UserController@destroy');
	Route::get('/matches', 'UserController@getMatches');
});
