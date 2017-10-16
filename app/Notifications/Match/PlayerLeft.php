<?php

namespace App\Notifications\Match;

use App\Mail\MailMessage;
use App\Models\Match;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlayerLeft extends Notification implements ShouldQueue
{
    use Queueable;
	/**
	 * @var Match
	 */
	protected $match;
	/**
	 * @var User
	 */
	protected $user;

	/**
	 * Create a new notification instance.
	 *
	 * @param Match $match
	 * @param User $user
	 */
    public function __construct(Match $match, User $user)
    {
        //
		$this->match = $match;
		$this->user = $user;
	}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
			->subject(__('mail/playerLeft.subject', [
				'match' => $this->match->name,
				'name' => $this->user->username,
			], $notifiable->language))
			->language($notifiable->language)
			->greeting(__('mail/global.hello', [], $notifiable->language) . ',')
			->line(__('mail/playerLeft.body', [
				'match' => $this->match->name,
				'name' => $this->user->username,
			], $notifiable->language))
			->action(__('mail/playerLeft.review', [], $notifiable->language), action('Match\MatchController@showMatch', $this->match))
			->salutation(__('mail/global.dontReply', [], $notifiable->language));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
