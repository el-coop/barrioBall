<?php

namespace App\Notifications\Match;

use App\Mail\MailMessage;
use App\Models\Match;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class JoinMatchRequest extends Notification implements ShouldQueue {
	use Queueable;

	protected $message;
	protected $match;
	protected $user;

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
	public function via($notifiable) {
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return MailMessage
	 */
	public function toMail($notifiable) {
		return (new MailMessage)
			->replyTo($this->user->email, $this->user->name)
			->language($notifiable->language)
			->subject(__('mail/joinMatchRequest.subject', [
				'match' => $this->match->name,
			], $notifiable->language))
			->greeting(__('mail/global.hello', [], $notifiable->language) . ',')
			->line(__('mail/joinMatchRequest.sentJoin', [
				'name' => $this->user->username,
				'url' => action('Match\MatchController@showMatch', $this->match),
				'match' => $this->match->name,
			], $notifiable->language))
			->quote($this->message)
			->action(__('mail/joinMatchRequest.review', [], $notifiable->language), action('Match\MatchController@showMatch', $this->match))
			->salutation(__('mail/global.replyToThisEmail', [], $notifiable->language));
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function toArray($notifiable) {
		return [
			//
		];
	}
}
