<?php

namespace App\Listeners\Match;

use App\Events\Match\DeletedOldMatch;
use App\Notifications\Match\OldMatchDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOldMatchDeletedMessage {
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
	 * @param DeletedOldMatch $event
	 *
	 * @return void
	 */
	public function handle(DeletedOldMatch $event): void {
		$event->match->managers->each(function ($manager) use ($event) {
			$manager->notify(new OldMatchDeleted($event->match, $manager));
		});
	}
}
