<?php

namespace App\Notifications\Match;

use App\Mail\MailMessage;
use App\Models\Match;
use Illuminate\Bus\Queueable;
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
	 * Create a new notification instance.
	 *
	 * @param Match $match
	 * @param string $message
	 */
	public function __construct(Match $match,string $message) {
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
	 * @return MailMessage
	 */
	public function toMail($notifiable) {
		return (new MailMessage)
			->subject(__('mail/userRejected.subject', [
				'name' => $this->match->name,
			], $notifiable->language))
			->language($notifiable->language)
			->greeting(__('mail/global.hello', [], $notifiable->language) . ',')
			->line(__('mail/userRejected.youWereRejected', [
				'url' => action('Match\MatchController@showMatch', $this->match),
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
	public function toArray($notifiable) {
		return [
			//
		];
	}
}
