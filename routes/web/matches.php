<?php

Route::group(['prefix' => "matches", 'namespace' => 'Match'],function(){
	Route::get('/', 'MatchController@showCreate');
	Route::post('/', 'MatchController@create');

	Route::get('/{match}', 'MatchController@showMatch');
});