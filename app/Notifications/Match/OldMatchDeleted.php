<?php

namespace App\Notifications\Match;

use App\Mail\MailMessage;
use App\Models\Match;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class OldMatchDeleted extends Notification implements ShouldQueue {
	use Queueable;
	/**
	 * @var Match
	 */
	public $match;

	/**
	 * Create a new notification instance.
	 *
	 * @param string $match
	 */
	public function __construct(string $match) {
		$this->match = $match;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function via($notifiable): array {
		return ['mail'];
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
			->subject(__('mail/oldMatchDeleted.subject', [
				'match' => $this->match,
			], $notifiable->language))
			->language($notifiable->language)
			->greeting(__('mail/global.hello', [], $notifiable->language) . ',')
			->line(__('mail/oldMatchDeleted.body', [
				'match' => $this->match,
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
	public function toArray($notifiable): array {
		return [
			//
		];
	}
}
