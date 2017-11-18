<?php

namespace App\Events\Match;

use App\Models\Match;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
	/**
	 * @var Match
	 */
	public $match;
	public $user;

	/**
	 * Create a new event instance.
	 *
	 * @param Match $match
	 */
    public function __construct(Match $match)
    {
        //
		$this->match = $match;
		$this->user = $match->managers()->first();
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
