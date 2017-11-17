<?php

namespace App\Notifications\Match;

use App\Mail\MailMessage;
use App\Models\Match;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class JoinRequestAccepted extends Notification implements ShouldQueue {
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
	 * @var
	 */
	protected $manager;


	/**
	 * Create a new notification instance.
	 *
	 * @param Match $match
	 * @param string $message
	 * @param User $manager
	 */
	public function __construct(Match $match, User $manager, string $message) {
		$this->match = $match;
		$this->message = $message;
		$this->manager = $manager;
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
	 * @param $notifiable
	 *
	 * @return MailMessage
	 */
	public function toMail($notifiable) {
		return (new MailMessage)
			->subject(__('mail/userAccepted.subject', [
				'name' => $this->match->name,
			], $notifiable->language))
			->replyTo($this->manager->email)
			->language($notifiable->language)
			->greeting(__('mail/global.hello', [], $notifiable->language) . ',')
			->line(__('mail/userAccepted.youWereAccepted', [
				'url' => $this->match->url,
				'name' => $this->match->name,
			], $notifiable->language))
			->line(__('mail/global.adminSays', [], $notifiable->language))
			->quote($this->message)
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
