<?php

Route::get('/', 'HomeController@welcome');
Route::get('/tech', 'HomeController@tech');
Route::get('/about', 'HomeController@about');
Route::get('/home', 'HomeController@index')->middleware('auth');

Route::group(['prefix' => 'contact'], function () {
	Route::get('/', 'HomeController@showContactUs');
	Route::post('/', 'HomeController@contactUs');
});

Route::get('lang/{lang}', 'LanguageController@switchLang');
Route::post('jserror', 'ErrorController@store');
