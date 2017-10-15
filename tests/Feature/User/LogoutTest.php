<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase {
	use RefreshDatabase;

	protected $user;

	public function setUp() {
		parent::setUp();
		$this->user = factory(User::class)->create();
	}

	/**
	 * @test
	 * @group user
	 * @group logout
	 */
	public function test_user_can_logout(): void {
		$this->post(action('Auth\LoginController@logout'))
			->assertRedirect(action('HomeController@welcome'));

		$this->assertGuest();
	}
}
