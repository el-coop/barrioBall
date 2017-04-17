<?php


Route::group(['prefix' => "search", 'namespace' => 'Match'],function(){
	Route::get('/', 'MatchController@showSearch');
	Route::post('/', 'MatchController@search');
});