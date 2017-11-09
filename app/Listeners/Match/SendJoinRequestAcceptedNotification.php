<?php

namespace App\Listeners\Match;

use App\Events\Match\PlayerJoined;
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
	 * @param  PlayerJoined $event
	 *
	 * @return void
	 */
	public function handle(PlayerJoined $event): void {
		if ($event->user != $event->manager) {
			$event->user->notify(new JoinRequestAccepted($event->match, $event->manager, $event->message ?? ''));
		}
	}
}
