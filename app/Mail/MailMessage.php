<?php

namespace App\Mail;


use Illuminate\Notifications\Action;
use Illuminate\Notifications\Messages\MailMessage as MailParent;

class MailMessage extends MailParent {
	public $quote;
	public $language;


	/**
	 * @param string $language
	 *
	 * @return MailMessage
	 */
	public function language(string $language): self {
		$this->language = $language;

		return $this;
	}

	/**
	 * @param string $quote
	 *
	 * @return MailMessage
	 */
	public function quote(string $quote): self {
		$this->quote = $quote;

		return $this;
	}

	/**
	 * @param array|Action|string $line
	 *
	 * @return MailMessage
	 */
	public function with($line): self {
		if ($line instanceof Action) {
			$this->action($line->text, $line->url);
		} else if (!$this->actionText && !$this->quote) {
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
	public function toArray(): array {
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
			'language' => $this->language,
		];
	}

}
