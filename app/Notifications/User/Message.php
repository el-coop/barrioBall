<?php

namespace App\Notifications\User;

use App\Channels\ConversationChannel;
use App\Models\Message as MessageModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Message extends Notification
{
    use Queueable;

    protected $message;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param string $message
     */
    public function __construct(User $user, string $message) {
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ConversationChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toConversation($notifiable){
        $message = new MessageModel;
        $message->text = $this->message;
        return $message;
    }
}
