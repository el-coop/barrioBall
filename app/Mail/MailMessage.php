<?php

namespace App\Mail;


use Illuminate\Notifications\Action;
use Illuminate\Notifications\Messages\MailMessage as MailParent;

class MailMessage extends MailParent
{
	public $quote;
	public $language;


	public function language($language){
		$this->language = $language;
		return $this;
	}

	public function quote($quote){
		$this->quote = $quote;
		return $this;
	}

	public function with($line)
	{
		if ($line instanceof Action) {
			$this->action($line->text, $line->url);
		} elseif (! $this->actionText && ! $this->quote) {
			$this->introLines[] = $this->formatLine($line);
		} else {
			$this->outroLines[] = $this->formatLine($line);
		}

		return $this;
	}

	/**
	 * Get an array representation of the message.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return [
			'level' => $this->level,
			'subject' => $this->subject,
			'greeting' => $this->greeting,
			'salutation' => $this->salutation,
			'introLines' => $this->introLines,
			'outroLines' => $this->outroLines,
			'actionText' => $this->actionText,
			'actionUrl' => $this->actionUrl,
			'quote' => $this->quote,
			'language' => $this->language
		];
	}

}
