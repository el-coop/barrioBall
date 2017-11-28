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

class ManagerJoined
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
	/**
	 * @var Match
	 */
	public $match;
	/**
	 * @var User
	 */
	public $user;

	/**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Match $match, User $user)
    {
		$this->match = $match;
		$this->user = $user;
	}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
