<?php

namespace App\Notifications\Match;

use App\Mail\MailMessage;
use App\Models\Match;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class OldMatchDeleted extends Notification
{
    use Queueable;
	/**
	 * @var Match
	 */
	public $match;
	/**
	 * @var User
	 */
	public $recipient;
	public $language;

	/**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Match $match, User $recipient)
    {
        //
		$this->match = $match;
		$this->recipient = $recipient;
		$this->language = $recipient->language;
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
			->subject(__('mail/oldMatchDeleted.subject',[
				'match' => $this->match->name
			], $this->language))
			->greeting(__('mail/global.hello',[],$this->language) . ',')
			->line(__('mail/oldMatchDeleted.body',[
				'match' => $this->match->name
			], $this->language))
			->salutation(__('mail/global.dontReply',[],$this->language));
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
