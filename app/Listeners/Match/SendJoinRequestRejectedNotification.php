<?php

namespace App\Listeners\Match;

use App\Events\Match\UserRejected;
use App\Notifications\Match\JoinRequestRejected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendJoinRequestRejectedNotification {
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
	 * @param  UserRejected $event
	 *
	 * @return void
	 */
	public function handle(UserRejected $event): void {
		$event->user->notify(new JoinRequestRejected($event->user, $event->match, $event->message));
	}
}
