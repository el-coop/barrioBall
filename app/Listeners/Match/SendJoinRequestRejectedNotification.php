<?php

namespace App\Listeners\Match;

use App\Events\Match\PlayerRejected;
use App\Notifications\Match\JoinRequestRejected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendJoinRequestRejectedNotification implements ShouldQueue {
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
	 * @param  PlayerRejected $event
	 *
	 * @return void
	 */
	public function handle(PlayerRejected $event): void {
		$event->user->notify(new JoinRequestRejected($event->match, $event->manager,$event->message ?? ''));
	}
}
