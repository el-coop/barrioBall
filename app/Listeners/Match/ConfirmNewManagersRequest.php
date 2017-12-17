<?php

namespace App\Listeners\Match;

use App\Events\Match\ManagerJoined;
use App\Events\Match\PlayerJoined;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmNewManagersRequest {
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
	 * @param ManagerJoined $event
	 *
	 * @return void
	 */
	public function handle(ManagerJoined $event): void {
		if ($event->user->sentRequest($event->match) && !$event->match->isFull()) {
			$event->match->addPlayer($event->user);
			$event->match->joinRequests()->detach($event->user);
			event(new PlayerJoined($event->match, $event->user));

		}
	}
}
