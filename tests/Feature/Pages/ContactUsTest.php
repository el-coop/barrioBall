<?php

namespace Tests\Feature\Pages;

use App\Events\Misc\ContactUsSent;
use App\Mail\ContactUs;
use App\Models\Admin;
use App\Models\User;
use Event;
use Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactUsTest extends TestCase {
	use RefreshDatabase;
	protected $admin;

	public function setUp() {
		parent::setUp();
		$this->admin = factory(User::class)->create([
			'user_type' => 'Admin',
			'user_id' => function () {
				return factory(Admin::class)->create()->id;
			},
		]);

	}

	/**
	 * @test
	 * @group global
	 * @group contact
	 */
	public function test_anyone_can_see_contact_page(): void {
		$this->get(action('HomeController@showContactUs'))
			->assertSee('<title>' . __('navbar.contactLink'));
	}


	/**
	 * @test
	 * @group global
	 * @group contact
	 */
	public function test_can_send_message(): void {
		Event::fake();
		$this->post(action('HomeController@contactUs'), [
			'email' => 'test@best.com',
			'subject' => 'other',
			'message' => 'This is a test! does it work?',
		])->assertSessionHas('alert', __('global.success'));

		Event::assertDispatched(ContactUsSent::class, function ($event) {
			return $event->email == 'test@best.com' && $event->subject == 'other' && $event->message == 'This is a test! does it work?';
		});
	}

	/**
	 * @test
	 * @group global
	 * @group contact
	 */
	public function test_validation(): void {
		Event::fake();
		$this->post(action('HomeController@contactUs'), [
			'email' => 'testcom',
			'subject' => 'other1',
			'message' => 'Thi',
		])->assertSessionHasErrors(['email', 'subject', 'message']);

		Event::assertNotDispatched(ContactUsSent::class, function ($event) {
			return $event->email == 'test@best.com' && $event->subject == 'other' && $event->message == 'This is a test! does it work?';
		});
	}

	/**
	 * @test
	 * @group global
	 * @group contact
	 */
	public function test_mail_being_sent(): void {
		Mail::fake();

		$this->post(action('HomeController@contactUs'), [
			'email' => 'test@best.com',
			'subject' => 'other',
			'message' => 'This is a test! does it work?',
		])->assertSessionHas('alert', __('global.success'));

		Mail::assertQueued(ContactUs::class, function ($message) {
			return $message->sender == 'test@best.com' && $message->content == 'This is a test! does it work?' && $message->topic == __('global/contact.other', [], $this->admin->language);
		});
	}

}
