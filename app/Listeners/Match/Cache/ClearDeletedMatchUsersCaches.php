<?php

namespace App\Listeners\Match\Cache;

use App\Events\Match\MatchDeleted;
use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearDeletedMatchUsersCaches {
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
	 * @param  $event
	 *
	 * @return void
	 */
	public function handle($event): void {
		if(isset($event->match) && is_object($event->match)){
			$players = $event->match->registeredPlayers;
			$managers = $event->match->managers;
		} else {
			$players = $event->players;
			$managers = $event->managers;
		}

		$players->each(function ($player) {
			Cache::tags("{$player->username}_played")->flush();
			Cache::forget(sha1("{$player->username}_nextMatch"));
		});

		$managers->each(function ($manager) {
			Cache::tags("{$manager->username}_managed")->flush();
			Cache::forget(sha1("{$manager->username}_hasManagedMatches"));
		});
	}
}
