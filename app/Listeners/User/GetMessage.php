<?php

namespace App\Listeners\User;

use App\Events\User\MessageSent;
use App\Notifications\User\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GetMessage
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
    public function handle(MessageSent $event)
    {
        $users = $event->conversation->users;
        $recipient = $users->firstWhere('id','!=', $event->sender->id);
        $recipient->notify(new Message($event->sender,$event->message));
    }
}
