<?php

namespace App\Notifications\Match;

use App\Channels\ConversationChannel;
use App\Mail\MailMessage;
use App\Models\Match;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class JoinRequestRejected extends Notification implements ShouldQueue {
	use Queueable;
	/**
	 * @var
	 */
	protected $match;
	/**
	 * @var
	 */
	protected $message;
	/**
	 * @var User
	 */
	public $user;
	protected $notifiable;

	/**
	 * Create a new notification instance.
	 *
	 * @param Match $match
	 * @param User $user
	 * @param string $message
	 */
	public function __construct(Match $match, User $user, string $message) {
		$this->match = $match;
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
		return ['mail', ConversationChannel::class, 'broadcast'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return MailMessage
	 */
	public function toMail($notifiable): MailMessage {
		return (new MailMessage)
			->subject(__('mail/userRejected.subject', [
				'name' => $this->match->name,
			], $notifiable->language))
			->language($notifiable->language)
			->greeting(__('mail/global.hello', [], $notifiable->language) . ',')
			->line(__('mail/userRejected.youWereRejected', [
				'url' => $this->match->url,
				'name' => $this->match->name,
			], $notifiable->language))
			->line(__('mail/global.adminSays', [], $notifiable->language))
			->quote($this->message)
			->salutation(__('mail/global.dontReply', [], $notifiable->language));
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
		$this->notifiable = $notifiable;

		return new BroadcastMessage([
			'conversation' => $this->user->getConversationWith($notifiable)->id,
			'message' => [
				'action' => __('conversations/conversation.rejected', [
					'name' => $this->match->name,
					'url' => $this->match->url,
				], $notifiable->language),
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
	 * @return Message
	 */
	public function toConversation($notifiable): Message {
		$message = new Message;
		$message->text = $this->message;
		$message->user_id = $this->user->id;
		$message->action = __('conversations/conversation.rejected', [
			'name' => $this->match->name,
			'url' => $this->match->url,
		], $notifiable->language);

		return $message;
	}

	/**
	 * @return array
	 */
	public function broadcastOn(): array {
		return [
			new PrivateChannel('App.Models.User.' . $this->user->id),
			new PrivateChannel('App.Models.User.' . $this->notifiable->id),
		];
	}


}
