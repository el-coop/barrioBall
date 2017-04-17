<?php

Route::group(['prefix' => "matches", 'namespace' => 'Match'],function(){
	Route::get('/', 'PagesController@welcome');
	Route::post('/', 'MatchController@create');
});