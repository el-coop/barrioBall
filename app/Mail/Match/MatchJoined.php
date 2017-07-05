<?php

namespace App\Mail\Match;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MatchJoined extends Mailable
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
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $match)
    {
        //
		$this->user = $user;
		$this->match = $match;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.match.matchJoined');
    }
}
