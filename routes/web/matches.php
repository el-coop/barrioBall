<?php

Route::group(['prefix' => "matches", 'namespace' => 'Match'],function(){
	Route::get('/', 'MatchController@showCreate');
	Route::post('/', 'MatchController@create');
	Route::delete('/matches/{match}', 'MatchController@delete');

	Route::get('/users','MatchUsersController@searchUsers');
	Route::get('/{match}', 'MatchController@showMatch');
	Route::post('/{match}/players', 'MatchUsersController@joinMatch');
	Route::delete('/{match}/players', 'MatchUsersController@leaveMatch');

	Route::post('/{match}/admins/invite', 'MatchUsersController@inviteManagers');
	Route::delete('/{match}/admins', 'MatchUsersController@stopManaging');
});