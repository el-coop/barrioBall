<?php

namespace App\Events\Admin\Error;

use App\Models\Errors\Error;
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
	 * @var Error
	 */
	public $error;

	/**
	 * Create a new event instance.
	 *
	 * @param Error $error
	 */
    public function __construct(Error $error)
    {
        //
		$this->error = $error;
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
