<?php

namespace App\Listeners\Match;

use App\Events\Match\PlayerLeft;
use App\Notifications\Match\PlayerLeft as PlayerLeftNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPlayerLeftNotification implements ShouldQueue {
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
	 * @param  PlayerLeft $event
	 *
	 * @return void
	 */
	public function handle(PlayerLeft $event) {
		$event->match->managers->each->notify(new PlayerLeftNotification($event->match, $event->user));
	}
}
