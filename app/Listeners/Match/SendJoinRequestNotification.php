<?php

namespace App\Listeners\Match;

use App\Events\Match\JoinRequestSent;
use App\Notifications\Match\JoinMatchRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendJoinRequestNotification implements ShouldQueue {
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
	 * @param  JoinRequestSent $event
	 *
	 * @return void
	 */
	public function handle(JoinRequestSent $event): void {
		$event->match->managers->each->notify(new JoinMatchRequest($event->match, $event->user, $event->message));
	}
}
