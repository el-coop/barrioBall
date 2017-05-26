<?php

namespace App\Events\Match;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserLeft
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

	protected $user;
	protected $match;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($user, $match)
	{
		$this->user = $user;
		$this->match = $match;
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
