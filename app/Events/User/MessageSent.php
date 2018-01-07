<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use App\Models\Conversation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $sender;
    public $message;
    public $conversation;

    /**
     * Create a new event instance.
     *
     * @param User $sender
     * @param string $message
     * @param Conversation $conversation
     */
    public function __construct(User $sender, string  $message, Conversation $conversation )
    {
        $this->sender = $sender;
        $this->conversation = $conversation;
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
