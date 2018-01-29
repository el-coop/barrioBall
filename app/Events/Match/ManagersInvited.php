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
use Illuminate\Support\Collection;

class ManagersInvited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
	public $managers;
	public $match;
	/**
	 * @var User
	 */
	public $manager;

	/**
	 * Create a new event instance.
	 *
	 * @param Match $match
	 * @param User $manager
	 * @param Collection $managers
	 */
    public function __construct(Match $match,User $manager,Collection $managers)
    {
		$this->managers = $managers;
		$this->match = $match;
		$this->manager = $manager;
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
