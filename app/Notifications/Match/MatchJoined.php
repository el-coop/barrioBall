<?php

namespace App\Notifications\Match;

use App\Mail\MailMessage;
use App\Models\Match;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class MatchJoined extends Notification implements ShouldQueue {
	use Queueable;

	protected $user;
	protected $match;

	/**
	 * Create a new notification instance.
	 *
	 * @param Match $match
	 * @param User $user
	 */
	public function __construct(Match $match,User $user) {
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
			->subject(__('mail/matchJoined.subject', [
				'match' => $this->match->name,
			], $notifiable->language))
			->language($notifiable->language)
			->greeting(__('mail/global.hello', [], $notifiable->language) . ',')
			->line(__('mail/matchJoined.hasBeenAuth', [
				'name' => $this->user->username,
				'url' => $this->match->url,
				'match' => $this->match->name,
			], $notifiable->language))
			->salutation(__('mail/global.dontReply', [], $notifiable->language));
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
