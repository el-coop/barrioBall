<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable implements ShouldQueue {
	use Queueable, SerializesModels;

	/**
	 * @var string
	 */
	/**
	 * @var string
	 */
	public $content;
	public $topic;
	/**
	 * @var string
	 */
	public $sender;

	/**
	 * Create a new message instance.
	 *
	 * @param string $sender
	 * @param string $subject
	 * @param string $message
	 */
	public function __construct(string $sender, string $subject, string $message) {
		//
		$this->topic = $subject;
		$this->content = $message;
		$this->sender = $sender;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build(): self {
		return $this->replyTo($this->sender)->subject($this->topic)->text('contact.mail');
	}
}
