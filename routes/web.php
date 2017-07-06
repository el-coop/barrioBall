<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
foreach (File::allFiles(__DIR__ . "/web") as $routeFile){
	require $routeFile;
}




Route::get('/home', 'HomeController@index');
Route::get('/', function(){
	return view('welcome');
});
Route::get('lang/{lang}', 'LanguageController@switchLang');

Route::post('jserror','ErrorController@store');

