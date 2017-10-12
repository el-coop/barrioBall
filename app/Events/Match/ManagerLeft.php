<?php

namespace App\Events\Match;

use App\Models\Match;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ManagerLeft
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

	protected $user;
	protected $match;

	/**
	 * Create a new event instance.
	 *
	 * @param User $user
	 * @param Match $match
	 */
	public function __construct(User $user, Match $match)
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
