<?php

namespace Tests\Feature\User;

use App\Events\User\Created;
use App\Listeners\Admin\Cache\ClearUserOverviewCache;
use App\Models\User;
use Cache;
use Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase {
	use RefreshDatabase;

	protected $user;

	public function setUp() {
		parent::setUp();
		$this->user = factory(User::class)->create();
	}

	/**
	 * @test
	 * @group user
	 * @group registration
	 */
	public function test_user_cant_see_register_page(): void {
		$this->actingAs($this->user)->get(action('Auth\RegisterController@showRegistrationForm'))
			->assertRedirect(action('HomeController@index'));
	}

	/**
	 * @test
	 * @group user
	 * @group registration
	 */
	public function test_guest_can_see_register_page(): void {
		$this->get(action('Auth\RegisterController@showRegistrationForm'))
			->assertSee('<title>' . __('navbar.registerLink'));
	}

	/**
	 * @test
	 * @group user
	 * @group registration
	 */
	public function test_user_cant_post_to_register(): void {
		$this->actingAs($this->user)->post(action('Auth\RegisterController@register'))
			->assertRedirect(action('HomeController@index'));
	}


	/**
	 * @test
	 * @group user
	 * @group registration
	 */
	public function test_guest_can_register(): void {
		Event::fake();
		$this->post(action('Auth\RegisterController@register'), [
			'username' => 'test',
			'email' => 'test@test.com',
			'password' => 'password',
			'password_confirmation' => 'password',
			'language' => 'en',
		])->assertRedirect(action('HomeController@index'));

		$this->assertAuthenticated();
		Event::assertDispatched(Created::class);
	}

	/**
	 * @test
	 * @group user
	 * @group registration
	 * @group adminOverview
	 */
	public function test_clears_admin_users_cache_on_register(): void {
		$user = factory(User::class)->create();
		Cache::shouldReceive('tags')->once()->with("admin_users")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');

		$listener = new ClearUserOverviewCache();
		$listener->handle(new Created($user));
	}

	/**
	 * @test
	 * @group user
	 * @group registration
	 */
	public function test_return_validation_errors(): void {
		$this->post(action('Auth\RegisterController@register'), [
			'username' => '',
			'email' => 'test',
			'password' => 'pas',
			'password_confirmation' => 'password',
		])->assertSessionHasErrors([
			'username',
			'email',
			'password',
			'language',
		]);

		$this->assertGuest();
	}
}
