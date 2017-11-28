<?php

namespace App\Notifications\Match;

use App\Mail\MailMessage;
use App\Models\Match;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManageInvitation extends Notification implements ShouldQueue {
	use Queueable;
	/**
	 * @var Match
	 */
	protected $match;

	/**
	 * Create a new notification instance.
	 *
	 * @param Match $match
	 */
	public function __construct(Match $match) {
		//
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
			->subject(__('mail/managerInvite.subject', [
				'match' => $this->match->name,
			], $notifiable->language))
			->language($notifiable->language)
			->greeting(__('mail/global.hello', [], $notifiable->language) . ',')
			->line(__('mail/managerInvite.body', [
				'match' => $this->match->name,
			], $notifiable->language))
			->action(__('mail/managerInvite.review', [], $notifiable->language), $this->match->url)
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
