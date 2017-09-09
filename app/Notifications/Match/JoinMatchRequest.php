<?php

namespace App\Notifications\Match;

use App\Mail\MailMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class JoinMatchRequest extends Notification implements ShouldQueue {
	use Queueable;

	protected $message;
	protected $match;
	protected $user;
	protected $language;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($user, $match, $message, User $recipient) {
		$this->message = $message;
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
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable) {
		return (new MailMessage)
			->replyTo($this->user->email,$this->user->name)
			->language($this->language)
			->subject(__('mail/joinMatchRequest.subject',[
				'match' => $this->match->name
			],$this->language))
			->greeting(__('mail/global.hello', [], $this->language) . ',')
			->line(__('mail/joinMatchRequest.sentJoin', [
					'name' => $this->user->username,
					'url' => action('Match\MatchController@showMatch', $this->match),
					'match' => $this->match->name
				], $this->language))
			->quote($this->message)
			->action(__('mail/joinMatchRequest.review',[],$this->language), action('Match\MatchController@showMatch', $this->match))
			->salutation(__('mail/global.replyToThisEmail', [], $this->language));
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
