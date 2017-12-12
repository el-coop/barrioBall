<?php

namespace Tests\Browser\Misc;

use App\Events\Misc\ContactUsSent;
use App\Listeners\Misc\SendContactUsMail;
use App\Models\Admin;
use App\Models\User;
use Event;
use Queue;
use Tests\Browser\Pages\Misc\ContactUsPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ContactUsTest extends DuskTestCase {
	use DatabaseMigrations;

	protected $admin;

	public function setUp() {
		parent::setUp();
		$this->admin = factory(User::class)->create([
			'user_id' => function () {
				return factory(Admin::class)->create()->id;
			},
			'user_type' => 'Admin',
		]);
	}

	/**
	 * @test
	 * @group contactUs
	 * @group global
	 */
	public function test_send_contact_us(): void {

		$this->browse(function (Browser $browser) {
			$browser->visit(new ContactUsPage)
				->assertSee(__('navbar.contactLink'))
				->submitForm([
					'email' => 'test@best.com',
					'message' => 'This is a message!',
				])->assertVisible('@sending')
				->waitForText('Success!')
				->click('@tryAgain')
				->assertVisible('@submit');
		});
	}

	/**
	 * @test
	 * @group contactUs
	 * @group global
	 */
	public function test_validation_contact_us(): void {

		$this->browse(function (Browser $browser) {
			$browser->visit(new ContactUsPage)
				->assertSee(__('navbar.contactLink'))
				->submitForm([
					'email' => 'test@best.com',
					'message' => 'Thi!',
				])->waitForText('Error!')
				->click('@tryAgain')
				->assertVisible('@submit');
		});
	}
}
