<?php

namespace App\Listeners\Misc;

use App\Events\Misc\ContactUsSent;
use App\Mail\ContactUs;
use App\Models\Admin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendContactUsMail implements ShouldQueue {
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct() {
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  object $event
	 *
	 * @return void
	 */
	public function handle(ContactUsSent $event): void {
		Mail::to(env('MAIL_CONTACT_EMAIL', config('mail.from.address')))
			->send(new ContactUs($event->email, __("global/contact.{$event->subject}", [], Admin::first()->user->language), $event->message));
	}
}
