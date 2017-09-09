<?php

namespace App\Notifications\Match;

use App\Mail\MailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

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
	 * @param $notifiable
	 *
	 * @return MailMessage
	 */
	public function toMail($notifiable)
    {
		return (new MailMessage)
			->subject(__('mail/userAccepted.subject',[
				'name' => $this->match->name
			], $this->language))
			->replyTo($this->manager->email)
			->greeting(__('mail/global.hello',[],$this->language) . ',')
			->line(__('mail/userAccepted.youWereAccepted',[
				'url' => action('Match\MatchController@showMatch', $this->match),
				'name' => $this->match->name
			], $this->language))
			->line(__('mail/global.adminSays',[], $this->language))
			->quote($this->message)
			->salutation(__('mail/global.replyToThisEmail',[],$this->language));
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
