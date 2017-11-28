<?php

namespace App\Notifications\Match;

use App\Mail\MailMessage;
use App\Models\Match;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManagerLeft extends Notification implements ShouldQueue {
	use Queueable;
	/**
	 * @var Match
	 */
	protected $match;
	/**
	 * @var User
	 */
	protected $manager;

	/**
	 * Create a new notification instance.
	 *
	 * @param Match $match
	 * @param User $manager
	 */
	public function __construct(Match $match, User $manager) {
		//
		$this->match = $match;
		$this->manager = $manager;
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
			->subject(__('mail/managerLeft.subject', [
				'match' => $this->match->name,
				'name' => $this->manager->username,
			], $notifiable->language))
			->language($notifiable->language)
			->greeting(__('mail/global.hello', [], $notifiable->language) . ',')
			->line(__('mail/managerLeft.body', [
				'match' => $this->match->name,
				'name' => $this->manager->username,
			], $notifiable->language))
			->action(__('mail/managerLeft.review', [], $notifiable->language), $this->match->url)
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
