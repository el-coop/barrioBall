<?php

namespace App\Listeners\Match\Cache;

use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearMatchCache {
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
		if (isset($event->match) && is_object($event->match)) {
			$id = $event->match->id;
		} else {
			$id = $event->id;
		}
		Cache::forget(sha1("match_{$id}"));
	}
}
