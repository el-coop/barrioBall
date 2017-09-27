<?php

Route::group(['prefix' => "matches", 'namespace' => 'Match'],function(){

	Route::get('/{match}', 'MatchController@showMatch');
	Route::group(['middleware' => ['auth']],function(){
		Route::get('/', 'MatchController@showCreate');
		Route::post('/', 'MatchController@create');
		Route::delete('/{match}', 'MatchController@delete');
		Route::get('/{match}/users','MatchUsersController@searchUsers');
		Route::post('/{match}/players', 'MatchUsersController@joinMatch');
		Route::delete('/{match}/players', 'MatchUsersController@leaveMatch');

		Route::post('/{match}/joinRequests','MatchUsersController@acceptJoin');
		Route::delete('/{match}/joinRequests','MatchUsersController@rejectJoin');

		Route::post('/{match}/admins/invite', 'MatchUsersController@inviteManagers');
		Route::delete('/{match}/admins', 'MatchUsersController@stopManaging');
	});
});