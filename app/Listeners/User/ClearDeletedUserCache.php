<?php

namespace App\Listeners\User;

use App\Events\User\Deleted;
use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearDeletedUserCache {
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
	 * @param  Deleted $event
	 *
	 * @return void
	 */
	public function handle(Deleted $event): void {
		foreach ($event->user->managedMatches as $match) {
			Cache::forget(sha1("{$match->id}_managers"));
		}
		foreach ($event->user->playedMatches as $match) {
			Cache::forget(sha1("{$match->id}_registeredPlayers"));
		}
		foreach ($event->user->joinRequests as $match) {
			Cache::forget(sha1("{$match->id}_joinRequests"));
		}
	}
}
