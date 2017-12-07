<?php

namespace Tests\Feature\User;

use App\Events\User\Deleted;
use App\Listeners\Admin\Cache\ClearUserOverviewCache;
use App\Listeners\User\ClearDeletedUserCache;
use App\Models\Match;
use App\Models\Player;
use App\Models\User;
use Cache;
use Event;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase {
	use RefreshDatabase;

	protected $user;

	public function setUp() {
		parent::setUp();
		$this->user = factory(User::class)->create([
			'language' => 'en',
		]);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_doesnt_show_profile_page_unauthorized(): void {
		$this->get(action('User\UserController@show'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));

	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_shows_profile_page(): void {
		$this->actingAs($this->user)->get(action('User\UserController@show'))
			->assertSee('<title>' . __('navbar.profileLink', [], $this->user->language));
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_unlogged_cant_update_username(): void {
		$this->patch(action('User\UserController@updateUsername'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_update_username(): void {

		$this->actingAs($this->user)->patch(action('User\UserController@updateUsername', [
			'username' => 'newUsername',
		]))->assertSessionHas('alert', __('profile/page.updatedUsername'));
		$this->assertEquals('newUsername', $this->user->username);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_cant_have_empty_username(): void {

		$this->actingAs($this->user)->patch(action('User\UserController@updateUsername', [
			'username' => '',
		]))->assertSessionHasErrors('username');
		$this->assertNotEquals('', $this->user->username);
	}


	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_unlogged_cant_update_password(): void {
		$this->patch(action('User\UserController@updatePassword'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}


	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_update_password(): void {
		$this->actingAs($this->user)->patch(action('User\UserController@updatePassword', [
			'password' => '12345678',
			'password_confirmation' => '12345678',
		]))->assertSessionHas('alert', __('profile/page.updatedPassword'));
		$this->assertTrue(Hash::check('12345678', $this->user->password));
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_cant_have_empty_password(): void {

		$this->actingAs($this->user)->patch(action('User\UserController@updatePassword', [
			'password' => '',
			'password_confirm' => '',
		]))->assertSessionHasErrors('password');
		$this->assertFalse(Hash::check('', $this->user->password));
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_cant_have_unconfirmed_password(): void {
		$this->actingAs($this->user)->patch(action('User\UserController@updatePassword', [
			'password' => '1234567',
			'password_confirm' => '123',
		]))->assertSessionHasErrors('password');
		$this->assertFalse(Hash::check('1234567', $this->user->password));
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_unlogged_cant_update_email(): void {
		$this->patch(action('User\UserController@updateEmail'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_update_email(): void {
		$this->actingAs($this->user)->patch(action('User\UserController@updateEmail', [
			'email' => 'new@new.new',
		]))->assertSessionHas('alert', __('profile/page.updatedEmail'));
		$this->assertEquals('new@new.new', $this->user->email);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_cant_have_empty_email(): void {

		$this->actingAs($this->user)->patch(action('User\UserController@updateEmail', [
			'email' => '',
		]))->assertSessionHasErrors('email');
		$this->assertNotEquals('', $this->user->email);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_cant_have_non_email(): void {
		$this->actingAs($this->user)->patch(action('User\UserController@updateEmail', [
			'email' => 'test',
		]))->assertSessionHasErrors('email');
		$this->assertNotEquals('test', $this->user->email);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_unlogged_cant_update_language(): void {
		$this->patch(action('User\UserController@updateLanguage'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_update_language(): void {
		$this->actingAs($this->user)->patch(action('User\UserController@updateLanguage', [
			'language' => 'es',
		]))->assertSessionHas('alert', __('profile/page.updatedLanguage', [], $this->user->language));
		$this->assertEquals('es', $this->user->language);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_cant_have_empty_language(): void {

		$this->actingAs($this->user)->patch(action('User\UserController@updateLanguage', [
			'language' => '',
		]))->assertSessionHasErrors('language');
		$this->assertNotEquals('', $this->user->language);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_cant_have_non_language(): void {

		$this->actingAs($this->user)->patch(action('User\UserController@updateLanguage', [
			'language' => 'test',
		]))->assertSessionHasErrors('language');
		$this->assertNotEquals('test', $this->user->language);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_unlogged_cant_delete_user(): void {
		$this->delete(action('User\UserController@delete'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
		$this->assertNotEquals(0, User::count());
		$this->assertNotEquals(0, Player::count());
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_delete_himself(): void {
		Event::fake();
		$this->actingAs($this->user)->delete(action('User\UserController@delete'));
		$this->assertEquals(0, User::count());
		$this->assertEquals(0, Player::count());
		Event::assertDispatched(Deleted::class, function ($event) {
			return $this->user->id == $event->user->id;
		});
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function refreshes_user_matches_caches_on_delete(): void {
		$match = factory(Match::class)->create();
		$matchWithRequest = factory(Match::class)->create();
		$match->addPlayer($this->user);
		$match->addManager($this->user);
		$matchWithRequest->addJoinRequest($this->user);

		Cache::shouldReceive('forget')->once()->with(sha1("{$matchWithRequest->id}_joinRequests"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$match->id}_managers"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$match->id}_registeredPlayers"));

		$listener = new ClearDeletedUserCache;
		$listener->handle(new Deleted($this->user));

	}


	/**
	 * @test
	 * @group user
	 * @group profile
	 * @group adminOverview
	 */
	public function test_clears_admin_users_cache_on_delete(): void {
		Cache::shouldReceive('tags')->once()->with("admin_users")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');

		$listener = new ClearUserOverviewCache();
		$listener->handle(new Deleted($this->user));
	}
}
