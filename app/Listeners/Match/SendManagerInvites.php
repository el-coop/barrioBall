<?php

namespace App\Listeners\Match;

use App\Events\Match\ManagersInvited;
use App\Notifications\Match\ManageInvitation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendManagerInvites {
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
	 * @param  ManagersInvited $event
	 *
	 * @return void
	 */
	public function handle(ManagersInvited $event): void {
		$event->managers->each->notify(new ManageInvitation($event->match));
	}
}
