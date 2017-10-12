<?php

namespace App\Listeners\Match;

use App\Notifications\Match\PlayerRemoved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPlayerRemovedNotification {
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
	 * @param  object $event
	 *
	 * @return void
	 */
	public function handle($event): void {
		$event->user->notify(new PlayerRemoved($event->user, $event->match, $event->message));
	}
}
