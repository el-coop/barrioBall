<?php

Route::group(['prefix' => "matches", 'namespace' => 'Match'],function(){

	Route::get('/{match}', 'MatchController@showMatch');

	Route::group(['middleware' => ['auth']],function(){
		Route::get('/', 'MatchController@showCreate');
		Route::post('/', 'MatchController@create');
		Route::delete('/{match}', 'MatchController@delete');
		Route::get('/{match}/users','MatchUserController@searchUsers');
		Route::post('/{match}/players', 'MatchUserController@joinMatch');
        Route::post('/{match}/cancel', 'MatchUserController@cancelJoinRequest');
		Route::patch('/{match}', 'MatchController@repeatMatch');

		Route::delete('/{match}/players', 'MatchUserController@leaveMatch');
		Route::delete('/{match}/player','MatchUserController@removePlayer');

		Route::post('/{match}/joinRequests','MatchUserController@acceptJoin');
		Route::delete('/{match}/joinRequests','MatchUserController@rejectJoin');

		Route::post('/{match}/admins/invite', 'MatchUserController@inviteManagers');
		Route::post('/{match}/admins/join', 'MatchUserController@joinAsManager');
		Route::delete('/{match}/admins', 'MatchUserController@stopManaging');
	});
});