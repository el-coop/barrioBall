<?php

namespace App\Channels;


use App\Models\Conversation;
use Illuminate\Notifications\Notification;

class ConversationChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toConversation($notifiable);
        $message->user_id = $notification->user->id;
        $conversation = $notification->user->getConversationWith($notifiable);
        if(!$conversation)
        {
            $conversation = New Conversation;
            $conversation->save();
            $conversation->users()->attach([$notification->user->id,$notifiable->id]);
        }
        $conversation->markAsUnread($notifiable);
        $conversation->messages()->save($message);
    }
}