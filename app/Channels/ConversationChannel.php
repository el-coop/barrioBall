<?php

namespace App\Channels;


use App\Models\Conversation;
use Illuminate\Notifications\Notification;

class ConversationChannel {
	/**
	 * Send the given notification.
	 *
	 * @param  mixed $notifiable
	 * @param  \Illuminate\Notifications\Notification $notification
	 *
	 * @return void
	 */
	public function send($notifiable, Notification $notification): void {
		$message = $notification->toConversation($notifiable);
		$conversation = $notifiable->getConversationWith($notification->user);
		$conversation->addMessage($message);
	}
}