<?php

namespace App\Listeners\Match;

use App\Events\Match\Edited;
use App\Notifications\Match\EditedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEditedNotification {
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
	public function handle(Edited $event): void {
		$event->match->registeredPlayers->concat($event->match->managers)->unique('id')->each->notify(new EditedNotification($event->match));
	}
}
