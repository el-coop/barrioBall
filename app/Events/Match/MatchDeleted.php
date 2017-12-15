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

class MatchDeleted {
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $user;
	public $name;
	public $managers;
	public $players;
	public $id;

	/**
	 * Create a new event instance.
	 *
	 * @param int $id
	 * @param string $name
	 * @param Collection $players
	 * @param Collection $managers
	 * @param User $user
	 */
	public function __construct(int $id, string $name, Collection $players, Collection $managers, User $user) {
		$this->id = $id;
		$this->players = $players;
		$this->managers = $managers;
		$this->name = $name;
		$this->user = $user;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return Channel|array
	 */
	public function broadcastOn() {
		return new PrivateChannel('channel-name');
	}
}
