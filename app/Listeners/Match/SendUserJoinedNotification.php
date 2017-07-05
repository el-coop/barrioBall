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
	public function handle(UserJoined $event) {
		foreach ($event->match->managers as $manager) {
			$manager->notify(new MatchJoined($event->user, $event->match, $event->message));
		}
	}
}

