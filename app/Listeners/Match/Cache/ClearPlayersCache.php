<?php

namespace App\Listeners\Match\Cache;

use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearPlayersCache {
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
		Cache::forget(sha1("{$event->match->id}_registeredPlayers"));
		if($event->match->players != 0){
			Cache::forget(sha1("{$event->match->id}_isFull"));
		}
	}
}
