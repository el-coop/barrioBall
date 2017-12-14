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
	public $managers;
	/**
	 * @var string
	 */
	public $match;
	/**
	 * @var Collection
	 */
	public $players;
	/**
	 * @var int
	 */
	public $id;

	/**
	 * Create a new event instance.
	 *
	 * @param Collection $managers
	 * @param Collection $players
	 * @param string $match
	 * @param int $id
	 */
	public function __construct(Collection $managers, Collection $players, string $match, int $id) {
		$this->managers = $managers;
		$this->match = $match;
		$this->players = $players;
		$this->id = $id;
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
