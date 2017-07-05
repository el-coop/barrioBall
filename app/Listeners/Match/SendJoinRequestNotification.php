<?php

namespace App\Listeners\Match;

use App\Events\Match\JoinRequest;
use App\Notifications\Match\JoinMatchRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendJoinRequestNotification implements ShouldQueue
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
     * @param  JoinRequest  $event
     * @return void
     */
    public function handle(JoinRequest $event)
    {
        foreach ($event->match->managers as $manager){
			$manager->notify(new JoinMatchRequest($event->user,$event->match, $event->message));
		}
    }
}
