<?php

namespace App\Events\Misc;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ContactUsSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
	/**
	 * @var string
	 */
	public $email;
	/**
	 * @var string
	 */
	public $subject;
	/**
	 * @var string
	 */
	public $message;

	/**
	 * Create a new event instance.
	 *
	 * @param string $email
	 * @param string $subject
	 * @param string $message
	 */
    public function __construct(string $email, string $subject, string $message)
    {
        //
		$this->email = $email;
		$this->subject = $subject;
		$this->message = $message;
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
