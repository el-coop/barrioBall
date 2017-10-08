<?php

namespace App\Listeners\Match;

use App\Events\Match\UserJoined;
use App\Notifications\Match\JoinRequestAccepted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendJoinRequestAcceptedNotification implements ShouldQueue {
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
		if ($event->user != $event->manager) {
			$event->user->notify(new JoinRequestAccepted($event->user, $event->match, $event->message, $event->manager));
		}
	}
}
