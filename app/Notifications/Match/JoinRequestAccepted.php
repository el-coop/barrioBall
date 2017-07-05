<?php

namespace App\Notifications\Match;

use App\Mail\Match\JoinRequestAccepted as RequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JoinRequestAccepted extends Notification
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
	 * @var
	 */
	protected $manager;

	/**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $match, $message, $manager)
    {
        //
		$this->user = $user;
		$this->match = $match;
		$this->message = $message;
		$this->manager = $manager;
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
        return (new RequestMail($this->user,$this->match,$this->message,$this->manager));
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
