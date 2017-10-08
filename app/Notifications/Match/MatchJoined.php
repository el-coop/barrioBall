<?php

namespace App\Notifications\Match;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MatchJoined extends Notification implements ShouldQueue {
	use Queueable;

	protected $user;
	protected $match;
	protected $language;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($user, $match, User $recipient) {
		$this->match = $match;
		$this->user = $user;
		$this->language = $recipient->language;
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
			], $this->language))
			->language($this->language)
			->greeting(__('mail/global.hello', [], $this->language) . ',')
			->line(__('mail/matchJoined.hasBeenAuth', [
				'name' => $this->user->username,
				'url' => action('Match\MatchController@showMatch', $this->match),
				'match' => $this->match->name,
			], $this->language))
			->salutation(__('mail/global.dontReply', [], $this->language));
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
