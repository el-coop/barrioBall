<?php

namespace App\Notifications\User;

use App\Mail\MailMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Notification implements ShouldQueue {
	use Queueable;

	/**
	 * The password reset token.
	 *
	 * @var string
	 */
	public $token;
	public $recipient;
	public $language;

	/**
	 * Create a notification instance.
	 *
	 * @param  string $token
	 *
	 * @param User $recipient
	 */
	public function __construct(string $token, User $recipient) {
		$this->token = $token;
		$this->recipient = $recipient;
		$this->language = $recipient->language;
	}

	/**
	 * Get the notification's channels.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function via($notifiable): array {
		return ['mail'];
	}

	/**
	 * Build the mail representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return MailMessage
	 */
	public function toMail($notifiable): MailMessage {
		return (new MailMessage)
			->language($this->language)
			->subject(__('mail/passwordReset.subject', [], $this->language))
			->line(__('mail/passwordReset.firstLine', [], $this->language))
			->action(__('mail/passwordReset.button', [], $this->language), url(config('app.url') . route('password.reset', $this->token, false)))
			->line(__('mail/passwordReset.lastLine', [], $this->language));
	}
}
