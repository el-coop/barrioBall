<?php

namespace App\Mail\Match;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JoinMatchRequest extends Mailable
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
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $match, $message)
    {
		$this->user = $user;
		$this->match = $match;
		$this->userMessage = $message;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo($this->user->email)
				->view('mail.match.joinMatchRequest');
    }
}
