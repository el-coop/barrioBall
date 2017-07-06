<?php

namespace App\Notifications\Match;

use App\Mail\Match\JoinRequestRejected as RequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JoinRequestRejected extends Notification
{
    use Queueable;
	/**
	 * @var
	 */
	protected $user;
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
     * @return void
     */
    public function __construct($user, $match, $message)
    {
        //
		$this->user = $user;
		$this->match = $match;
		$this->message = $message;
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
        return (new RequestMail($this->user,$this->match,$this->message));
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
