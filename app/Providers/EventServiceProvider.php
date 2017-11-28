<?php

namespace App\Providers;

use App\Events\Match\Created;
use App\Events\Match\DeletedOldMatch;
use App\Events\Match\JoinRequestCenceled;
use App\Events\Match\JoinRequestSent;
use App\Events\Match\ManagerLeft;
use App\Events\Match\ManagersInvited;
use App\Events\Match\MatchDeleted;
use App\Events\Match\PlayerRemoved;
use App\Events\Match\PlayerLeft;
use App\Events\Match\PlayerRejected;
use App\Events\Match\PlayerJoined;
use App\Events\User\Deleted;
use App\Listeners\Match\Cache\ClearDeletedMatchUsersCaches;
use App\Listeners\Match\Cache\ClearJoinRequestsCache;
use App\Listeners\Match\Cache\ClearManagersCache;
use App\Listeners\Match\Cache\ClearPlayersCache;
use App\Listeners\Match\Cache\ClearUserJoinRequests;
use App\Listeners\Match\Cache\ClearUserManagedMatches;
use App\Listeners\Match\Cache\ClearUserMatchManagerInvitation;
use App\Listeners\Match\Cache\ClearUserPlayedMatches;
use App\Listeners\Match\SendJoinRequestAcceptedNotification;
use App\Listeners\Match\SendJoinRequestNotification;
use App\Listeners\Match\SendJoinRequestRejectedNotification;
use App\Listeners\Match\SendManagerInvites;
use App\Listeners\Match\SendManagerLeftNotification;
use App\Listeners\Match\SendMatchDeletedNotification;
use App\Listeners\Match\SendOldMatchDeletedMessage;
use App\Listeners\Match\SendPlayerLeftNotification;
use App\Listeners\Match\SendPlayerRemovedNotification;
use App\Listeners\Match\SendUserJoinedNotification;
use App\Listeners\User\ClearDeletedUserCache;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		Created::class => [
			ClearUserManagedMatches::class,
		],
		JoinRequestSent::class => [
			SendJoinRequestNotification::class,
			ClearJoinRequestsCache::class,
			ClearUserJoinRequests::class,
		],
		JoinRequestCenceled::class => [
			ClearJoinRequestsCache::class,
			ClearUserJoinRequests::class,
		],
		PlayerJoined::class => [
			SendUserJoinedNotification::class,
			SendJoinRequestAcceptedNotification::class,
			ClearUserPlayedMatches::class,
			ClearJoinRequestsCache::class,
			ClearPlayersCache::class,
			ClearUserJoinRequests::class,
		],
		PlayerLeft::class => [
			SendPlayerLeftNotification::class,
			ClearUserPlayedMatches::class,
			ClearPlayersCache::class,
		],
		PlayerRejected::class => [
			SendJoinRequestRejectedNotification::class,
			ClearJoinRequestsCache::class,
			ClearUserJoinRequests::class,
		],
		PlayerRemoved::class => [
			SendPlayerRemovedNotification::class,
			ClearUserPlayedMatches::class,
			ClearPlayersCache::class,
		],
		DeletedOldMatch::class => [
			SendOldMatchDeletedMessage::class,
			ClearDeletedMatchUsersCaches::class,
		],
		MatchDeleted::class => [
			SendMatchDeletedNotification::class,
			ClearDeletedMatchUsersCaches::class,
		],
		ManagerLeft::class => [
			SendManagerLeftNotification::class,
			ClearUserManagedMatches::class,
			ClearManagersCache::class,
		],
		Deleted::class => [
			ClearDeletedUserCache::class
		],
		ManagersInvited::class => [
			SendManagerInvites::class,
			ClearUserMatchManagerInvitation::class
		]
	];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot() {
		parent::boot();

		//
	}
}
