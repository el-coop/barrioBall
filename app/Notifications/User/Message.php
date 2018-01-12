<?php

namespace App\Notifications\User;

use App\Channels\ConversationChannel;
use App\Models\Message as MessageModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Message extends Notification {
	use Queueable;

	public $user;
	protected $message;

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
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function via($notifiable): array {
		return [ConversationChannel::class, 'broadcast'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable): MailMessage {
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function toArray($notifiable): array {
		return [
			//
		];
	}

	/**
	 * @param $notifiable
	 *
	 * @return BroadcastMessage
	 */
	public function toBroadcast($notifiable): BroadcastMessage {
		return new BroadcastMessage([
			'conversation' => $this->user->getConversationWith($notifiable)->id,
			'message' => [
				'action_type' => null,
				'action_match' => null,
				'action_match_id' => null,
				'text' => $this->message,
				'user_id' => $this->user->id,
				'date' => Carbon::now()->format('d/m/y'),
				'time' => Carbon::now()->format('H:i'),
			],
		]);
	}

	/**
	 * @param $notifiable
	 *
	 * @return MessageModel
	 */
	public function toConversation($notifiable): MessageModel {
		$message = new MessageModel;
		$message->text = $this->message;

		return $message;
	}
}
