<?php

namespace Tests\Feature\Pages;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactUsTest extends TestCase
{
	use RefreshDatabase;
	protected $player;

	public function setUp() {
		parent::setUp();
		$this->player = factory(User::class)->create();
	}

	/**
	 * @test
	 * @group global
	 * @group dashboard
	 */
	public function test_anyone_can_see_contact_page(): void {
		$this->get(action('HomeController@showContactUs'))
			->assertSee('<title>' . __('navbar.contactLink'));
	}

}
