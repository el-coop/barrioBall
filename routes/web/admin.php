<?php


Route::group(['prefix' => "admin", 'namespace' => 'Admin', 'middleware' => ['auth','onlyAdmin']],function(){

	Route::get('/','PageController@index');
	Route::get('/getUsers','PageController@getUsers');
	Route::get('/getMatches','PageController@getMatches');

	Route::group(['prefix' => "errors"],function(){
		Route::get('/', 'ErrorController@show');
		Route::get('/php', 'ErrorController@getPhpErrors');
		Route::get('/js', 'ErrorController@getJsErrors');
		Route::delete('/{error}', 'ErrorController@delete');
	});
});