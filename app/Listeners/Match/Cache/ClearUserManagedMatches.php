<?php

namespace App\Listeners\Match\Cache;

use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearUserManagedMatches {
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
		Cache::tags("{$event->user->username}_managed")->flush();
		Cache::forget(sha1("{$event->user->username}_hasManagedMatches"));
		Cache::forget(sha1("{$event->user->id}_{$event->match->id}_manager"));
	}
}
