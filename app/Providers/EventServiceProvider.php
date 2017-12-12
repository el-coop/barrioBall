<?php

namespace App\Providers;

use App\Events\Admin\Error\Created as ErrorCreated;
use App\Events\Admin\Error\Resolved;
use App\Events\Match\Created;
use App\Events\Match\DeletedOldMatch;
use App\Events\Match\Edited;
use App\Events\Match\JoinRequestCanceled;
use App\Events\Match\JoinRequestSent;
use App\Events\Match\ManageInvitationRejected;
use App\Events\Match\ManagerJoined;
use App\Events\Match\ManagerLeft;
use App\Events\Match\ManagersInvited;
use App\Events\Match\MatchDeleted;
use App\Events\Match\PlayerRemoved;
use App\Events\Match\PlayerLeft;
use App\Events\Match\PlayerRejected;
use App\Events\Match\PlayerJoined;
use App\Events\Misc\ContactUsSent;
use App\Events\User\Created as UserCreated;
use App\Events\User\Deleted;
use App\Listeners\Admin\Cache\ClearErrorsOverviewCache;
use App\Listeners\Admin\Cache\ClearMatchOverviewCache;
use App\Listeners\Admin\Cache\ClearUserOverviewCache;
use App\Listeners\Match\Cache\ClearDeletedMatchUsersCaches;
use App\Listeners\Match\Cache\ClearJoinRequestsCache;
use App\Listeners\Match\Cache\ClearManagersCache;
use App\Listeners\Match\Cache\ClearMatchCache;
use App\Listeners\Match\Cache\ClearPlayersCache;
use App\Listeners\Match\Cache\ClearUserJoinRequests;
use App\Listeners\Match\Cache\ClearUserManagedMatches;
use App\Listeners\Match\Cache\ClearUserMatchManagerInvitation;
use App\Listeners\Match\Cache\ClearUserPendingRequestCache;
use App\Listeners\Match\Cache\ClearUsersMatchManagerInvitations;
use App\Listeners\Match\Cache\ClearUserPlayedMatches;
use App\Listeners\Match\SendEditedNotification;
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
use App\Listeners\Misc\SendContactUsMail;
use App\Listeners\User\ClearDeletedUserCache;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		ErrorCreated::class => [
			ClearErrorsOverviewCache::class,
		],
		Resolved::class => [
			ClearErrorsOverviewCache::class,
		],
		Created::class => [
			ClearUserManagedMatches::class,
			ClearMatchOverviewCache::class,
		],
		JoinRequestSent::class => [
			SendJoinRequestNotification::class,
			ClearJoinRequestsCache::class,
			ClearUserJoinRequests::class,
			ClearUserPendingRequestCache::class,
		],
		JoinRequestCanceled::class => [
			ClearJoinRequestsCache::class,
			ClearUserJoinRequests::class,
			ClearUserPendingRequestCache::class,
		],
		PlayerJoined::class => [
			SendUserJoinedNotification::class,
			SendJoinRequestAcceptedNotification::class,
			ClearUserPlayedMatches::class,
			ClearJoinRequestsCache::class,
			ClearPlayersCache::class,
			ClearUserJoinRequests::class,
			ClearUserPendingRequestCache::class,
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
			ClearUserPendingRequestCache::class,
		],
		PlayerRemoved::class => [
			SendPlayerRemovedNotification::class,
			ClearUserPlayedMatches::class,
			ClearPlayersCache::class,
		],
		DeletedOldMatch::class => [
			SendOldMatchDeletedMessage::class,
			ClearDeletedMatchUsersCaches::class,
			ClearMatchOverviewCache::class,
			ClearMatchCache::class,
		],
		MatchDeleted::class => [
			SendMatchDeletedNotification::class,
			ClearDeletedMatchUsersCaches::class,
			ClearMatchOverviewCache::class,
			ClearUserPendingRequestCache::class,
			ClearMatchCache::class,
		],
		ManagerLeft::class => [
			SendManagerLeftNotification::class,
			ClearUserManagedMatches::class,
			ClearManagersCache::class,
			ClearUserPendingRequestCache::class,
		],
		Deleted::class => [
			ClearDeletedUserCache::class,
			ClearUserOverviewCache::class,
		],
		ManagersInvited::class => [
			SendManagerInvites::class,
			ClearUsersMatchManagerInvitations::class,
		],
		ManagerJoined::class => [
			ClearUserManagedMatches::class,
			ClearUserMatchManagerInvitation::class,
			ClearManagersCache::class,
			ClearUserPendingRequestCache::class,
		],
		ManageInvitationRejected::class => [
			ClearUserMatchManagerInvitation::class,
		],
		Edited::class => [
			SendEditedNotification::class,
		],
		UserCreated::class => [
			ClearUserOverviewCache::class,
		],
		ContactUsSent::class => [
			SendContactUsMail::class,
		],
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
