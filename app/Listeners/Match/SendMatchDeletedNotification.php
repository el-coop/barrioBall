<?php

namespace App\Listeners\Match;

use App\Events\Match\MatchDeleted;
use App\Notifications\Match\Deleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMatchDeletedNotification implements ShouldQueue {
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
	 * @param  MatchDeleted $event
	 *
	 * @return void
	 */
	public function handle(MatchDeleted $event): void {
		$event->players->concat($event->managers)->unique('id')->each->notify(new Deleted($event->name, $event->user));
	}
}
