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
		if (!$conversation = $notifiable->getConversationWith($notification->user)) {
			$conversation = New Conversation;
			$conversation->save();
			$conversation->users()->attach([$notification->user->id, $notifiable->id]);
		}
		$conversation->touch();
		$conversation->markAsUnread($notifiable);
		$conversation->messages()->save($message);
	}
}