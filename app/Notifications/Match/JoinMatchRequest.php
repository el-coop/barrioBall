<?php

namespace App\Notifications\Match;

use App\Channels\ConversationChannel;
use App\Mail\MailMessage;
use App\Models\Match;
use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class JoinMatchRequest extends Notification implements ShouldQueue {
	use Queueable;

	public $user;
	protected $message;
	protected $match;

	/**
	 * Create a new notification instance.
	 *
	 * @param Match $match
	 * @param User $user
	 * @param string $message
	 */
	public function __construct(Match $match, User $user, string $message) {
		$this->message = $message;
		$this->match = $match;
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
			->replyTo($this->user->email, $this->user->name)
			->language($notifiable->language)
			->subject(__('mail/joinMatchRequest.subject', [
				'match' => $this->match->name,
			], $notifiable->language))
			->greeting(__('mail/global.hello', [], $notifiable->language) . ',')
			->line(__('mail/joinMatchRequest.sentJoin', [
				'name' => $this->user->username,
				'url' => $this->match->url,
				'match' => $this->match->name,
			], $notifiable->language))
			->quote($this->message)
			->action(__('mail/joinMatchRequest.review', [], $notifiable->language), $this->match->url)
			->salutation(__('mail/global.replyToThisEmail', [], $notifiable->language));
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
			'match_url' => $this->match->url,
			'match_name' => $this->match->name,
			'conversation' => $this->user->getConversationWith($notifiable)->id,
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
		$message->action_type = (__('conversations/conversation.join', [], $notifiable->language));
		$message->action_match = $this->match->name;
		$message->action_match_id = $this->match->id;

		return $message;
	}
}
