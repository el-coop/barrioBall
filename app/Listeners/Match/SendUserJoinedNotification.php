<?php

namespace App\Listeners\Match;

use App\Events\Match\UserJoined;
use App\Notifications\Match\MatchJoined;
use Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserJoinedNotification implements ShouldQueue {
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct() {
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  UserJoined $event
	 *
	 * @return void
	 */
	public function handle(UserJoined $event): void {

		$event->match->managers->reject(function ($manager) use ($event) {
			return $manager == $event->user;
		})->each->notify(new MatchJoined($event->match, $event->user));
	}
}

