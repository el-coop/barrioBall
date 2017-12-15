<?php

namespace App\Listeners\Match\Cache;

use App\Events\Match\ManagerLeft;
use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearUserPendingRequestCache
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
    public function handle(ManagerLeft $event)
    {
			Cache::forget(sha1("{$event->user->username}_requests"));
    }
}
