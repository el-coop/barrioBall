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
use Illuminate\Support\Collection;

class ManagersInvited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
	public $managers;
	public $match;

	/**
	 * Create a new event instance.
	 *
	 * @param Match $match
	 * @param Collection $managers
	 */
    public function __construct(Match $match,Collection $managers)
    {
		$this->managers = $managers;
		$this->match = $match;
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
