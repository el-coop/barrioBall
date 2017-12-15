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

class DeletedOldMatch {
	use Dispatchable, InteractsWithSockets, SerializesModels;
	/**
	 * @var Collection
	 */
	public $players;
	/**
	 * @var Collection
	 */
	public $managers;
	/**
	 * @var int
	 */
	public $id;
	/**
	 * @var string
	 */
	public $name;

	/**
	 * Create a new event instance.
	 *
	 * @param Collection $players
	 * @param Collection $managers
	 * @param string $name
	 * @param int $id
	 */
	public function __construct(Collection $players, Collection $managers, string $name, int $id) {
		$this->players = $players;
		$this->managers = $managers;
		$this->id = $id;
		$this->name = $name;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn() {
		return new PrivateChannel('channel-name');
	}
}
