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

class MatchDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

	public $match;
	public $user;

	/**
	 * Create a new event instance.
	 *
	 * @param User $user
	 * @param Match $match
	 */
    public function __construct(Match $match, User $user)
    {
        $this->match = $match;
		$this->user = $user;
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
