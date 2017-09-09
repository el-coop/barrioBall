<?php

namespace App\Events\Match;

use App\Models\Match;
use App\Models\User;
use Auth;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserJoined
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $match;
    public $manager;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Match $match, $message = '')
    {
        $this->user = $user;
		$this->match = $match;
		$this->manager = Auth::user();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}
