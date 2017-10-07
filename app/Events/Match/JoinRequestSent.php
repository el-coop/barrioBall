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

class JoinRequestSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

	public $user;
	public $match;
	public $message;

	/**
	 * Create a new event instance.
	 *
	 * @param User $user
	 * @param Match $match
	 * @param string $message
	 */
    public function __construct(User $user, Match $match, string $message)
    {
		$this->user = $user;
		$this->match = $match;
		$this->message = $message;
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
