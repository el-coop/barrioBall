<?php

namespace App\Channels;


use App\Models\Conversation;
use Cache;
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

		Cache::forget(sha1("{$notifiable->username}_conversations"));
		Cache::forget(sha1("{$notifiable->username}_{$conversation->id}_conversation"));
		Cache::forget(sha1("{$notification->user->username}_conversations"));
		Cache::forget(sha1("{$notification->user->username}_{$conversation->id}_conversation"));
	}
}