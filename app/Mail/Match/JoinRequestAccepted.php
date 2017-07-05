<?php

namespace App\Mail\Match;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JoinRequestAccepted extends Mailable
{
    use Queueable, SerializesModels;
	/**
	 * @var
	 */
	public $user;
	/**
	 * @var
	 */
	public $match;
	/**
	 * @var
	 */
	public $userMessage;
	/**
	 * @var
	 */
	public $manager;

	/**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$match,$message, $manager)
    {
		$this->user = $user;
		$this->match = $match;
		$this->userMessage = $message;
		$this->manager = $manager;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo($this->manager->email)
		->view('mail.match.joinRequestAccepted');
    }
}
