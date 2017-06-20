<?php

namespace App\Notifications\Match;

use App\Mail\Match\JoinMatchRequest as RequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JoinMatchRequest extends Notification {
	use Queueable;

	protected $message;
	protected $match;
	protected $user;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($user, $match, $message) {
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
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable) {
		return (new RequestMail($this->user,$this->match,$this->message));
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
