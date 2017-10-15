<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase {
	use RefreshDatabase;

	protected $user;

	public function setUp() {
		parent::setUp();
		$this->user = factory(User::class)->create([
			'password' => bcrypt('password')
		]);
	}


	/**
	 * @test
	 * @group user
	 * @group login
	 */
	public function test_logged_in_cant_see_login_page(): void {
		$this->actingAs($this->user)->get(action('Auth\LoginController@showLoginForm'))
			->assertRedirect(action('HomeController@index'));

	}

	/**
	 * @test
	 * @group user
	 * @group login
	 */
	public function test_guest_can_see_login_page(): void {
		$this->get(action('Auth\LoginController@showLoginForm'))
			->assertSee('<title>' . __('navbar.loginLink'));

	}

	/**
	 * @test
	 * @group user
	 * @group login
	 */
	public function test_logged_in_cant_log_in(): void {
		$this->actingAs($this->user)->post(action('Auth\LoginController@login'))
			->assertRedirect(action('HomeController@index'));

	}

	/**
	 * @test
	 * @group user
	 * @group login
	 */
	public function test_guest_can_login(): void {
		$this->post(action('Auth\LoginController@login'),[
			'email' => $this->user->email,
			'password' => 'password'
		])->assertRedirect(action('HomeController@index'));

		$this->assertAuthenticated();
	}

	/**
	 * @test
	 * @group user
	 * @group login
	 */
	public function test_user_cant_login_with_wrong_password(): void {
		$this->post(action('Auth\LoginController@login'),[
			'email' => $this->user->email,
			'password' => 'bla'
		])->assertRedirect(action('HomeController@welcome'))
			->assertSessionHasErrors('email');

		$this->assertGuest();
	}

	/**
	 * @test
	 * @group user
	 * @group login
	 */
	public function test_user_cant_login_with_wrong_email(): void {
		$this->post(action('Auth\LoginController@login'),[
			'email' => 'bla@gla.com',
			'password' => 'password'
		])->assertRedirect(action('HomeController@welcome'))
			->assertSessionHasErrors('email');

		$this->assertGuest();
	}
}
