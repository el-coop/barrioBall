<?php

namespace App\Listeners\Match\Cache;

use App\Events\Match\ManagersInvited;
use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearUserMatchManagerInvitation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ManagersInvited  $event
     * @return void
     */
    public function handle(ManagersInvited $event)
    {
		$event->managers->each(function($player) use($event){
			Cache::forget(sha1("{$player->id}_{$event->match->id}_managerInvitation"));
		});
    }
}
