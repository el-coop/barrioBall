<?php

namespace App\Providers;

use App\Events\Match\DeletedOldMatch;
use App\Events\Match\JoinRequestSent;
use App\Events\Match\ManagerLeft;
use App\Events\Match\MatchDeleted;
use App\Events\Match\PlayerRemoved;
use App\Events\Match\PlayerLeft;
use App\Events\Match\PlayerRejected;
use App\Events\Match\PlayerJoined;
use App\Listeners\Match\SendJoinRequestAcceptedNotification;
use App\Listeners\Match\SendJoinRequestNotification;
use App\Listeners\Match\SendJoinRequestRejectedNotification;
use App\Listeners\Match\SendManagerLeftNotification;
use App\Listeners\Match\SendMatchDeletedNotification;
use App\Listeners\Match\SendOldMatchDeletedMessage;
use App\Listeners\Match\SendPlayerLeftNotification;
use App\Listeners\Match\SendPlayerRemovedNotification;
use App\Listeners\Match\SendUserJoinedNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		JoinRequestSent::class => [
			SendJoinRequestNotification::class,
		],
		PlayerJoined::class => [
			SendUserJoinedNotification::class,
			SendJoinRequestAcceptedNotification::class
		],
		PlayerLeft::class => [
			SendPlayerLeftNotification::class
		],
		PlayerRejected::class => [
			SendJoinRequestRejectedNotification::class
		],
		PlayerRemoved::class => [
			SendPlayerRemovedNotification::class
		],
		DeletedOldMatch::class => [
			SendOldMatchDeletedMessage::class
		],
		MatchDeleted::class => [
			SendMatchDeletedNotification::class
		],
		ManagerLeft::class => [
			SendManagerLeftNotification::class
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
