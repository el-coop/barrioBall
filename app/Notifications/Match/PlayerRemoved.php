<?php

namespace App\Notifications\Match;

use App\Mail\MailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlayerRemoved extends Notification implements ShouldQueue {
	use Queueable;

	protected $language;
	protected $match;
	protected $message;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($user, $match, $message) {
		$this->language = $user->language;
		$this->match = $match;
		$this->message = $message;
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
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable) {
		return (new MailMessage)
			->subject(__('mail/playerRemoved.subject',[
				'match' => $this->match->name
			], $this->language))
			->greeting(__('mail/global.hello', [], $this->language) . ',')
			->line(__('mail/playerRemoved.youWereRemoved',[
				'url' => action('Match\MatchController@showMatch', $this->match),
				'match' => $this->match->name
			], $this->language))
			->line(__('mail/global.adminSays', [], $this->language))
			->quote($this->message)
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
