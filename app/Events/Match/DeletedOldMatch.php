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

class DeletedOldMatch {
	use Dispatchable, InteractsWithSockets, SerializesModels;
	public $match;

	/**
	 * Create a new event instance.
	 *
	 * @param Match $match
	 */
	public function __construct(Match $match) {
		$this->match = $match;
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
