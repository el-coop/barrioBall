<?php

namespace App\Listeners\Match;

use App\Events\Match\ManagerLeft;
use App\Notifications\Match\ManagerLeft as Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendManagerLeftNotification {
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
	 * @param  ManagerLeft $event
	 *
	 * @return void
	 */
	public function handle(ManagerLeft $event): void {
		$event->match->managers->each->notify(new Notification($event->match, $event->manager));
	}
}
