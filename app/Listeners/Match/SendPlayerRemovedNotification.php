<?php

namespace App\Listeners\Match;

use App\Events\Match\PlayerRemoved;
use App\Notifications\Match\PlayerRemoved as Notification;
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
	 * @param PlayerRemoved $event
	 *
	 * @return void
	 */
	public function handle(PlayerRemoved $event): void {
		$event->user->notify(new Notification($event->match, $event->message));
	}
}
