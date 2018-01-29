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

class PlayerRemoved {
	use Dispatchable, InteractsWithSockets, SerializesModels;
	public $user;
	public $match;
	public $message;
	/**
	 * @var User
	 */
	public $manager;

	/**
	 * Create a new event instance.
	 *
	 * @param Match $match
	 * @param User $manager
	 * @param User $user
	 * @param string $message
	 */
	public function __construct(Match $match, User $manager, User $user, ?string $message = '') {
		$this->user = $user;
		$this->match = $match;
		$this->message = $message;
		$this->manager = $manager;
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
