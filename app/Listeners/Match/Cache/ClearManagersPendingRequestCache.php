<?php

namespace App\Listeners\Match\Cache;

use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearManagersPendingRequestCache
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
    	if(isset($event->match)){
			$event->match->managers->each(function ($manager) {
				Cache::forget(sha1("{$manager->username}_requests"));
			});
		} else {
			$event->managers->each(function ($manager) {
				Cache::forget(sha1("{$manager->username}_requests"));
			});
		}
	}
}
