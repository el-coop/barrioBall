<?php

namespace Tests\Feature\User;

use App\Models\Admin;
use App\Models\Player;
use App\Models\User;
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
		$this->get(action('UserController@show'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));

	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_shows_profile_page(): void {
		$this->actingAs($this->user)->get(action('UserController@show'))
			->assertSee('<title>' . __('navbar.profileLink', [], $this->user->language));
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_unlogged_cant_update_username(): void {
		$this->patch(action('UserController@updateUsername'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_update_username(): void {

		$this->actingAs($this->user)->patch(action('UserController@updateUsername', [
			'username' => 'newUsername',
		]))->assertSessionHas('alert',__('profile/page.updatedUsername'));
		$this->assertEquals('newUsername', $this->user->username);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_cant_have_empty_username(): void {

		$this->actingAs($this->user)->patch(action('UserController@updateUsername', [
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
		$this->patch(action('UserController@updatePassword'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}


	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_update_password(): void {
		$this->actingAs($this->user)->patch(action('UserController@updatePassword', [
			'password' => '12345678',
			'password_confirmation' => '12345678',
		]))->assertSessionHas('alert',__('profile/page.updatedPassword'));
		$this->assertTrue(Hash::check('12345678', $this->user->password));
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_cant_have_empty_password(): void {

		$this->actingAs($this->user)->patch(action('UserController@updatePassword', [
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
		$this->actingAs($this->user)->patch(action('UserController@updatePassword', [
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
		$this->patch(action('UserController@updateEmail'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_update_email(): void {
		$this->actingAs($this->user)->patch(action('UserController@updateEmail', [
			'email' => 'new@new.new',
		]))->assertSessionHas('alert',__('profile/page.updatedEmail'));
		$this->assertEquals('new@new.new', $this->user->email);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_cant_have_empty_email(): void {

		$this->actingAs($this->user)->patch(action('UserController@updateEmail', [
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
		$this->actingAs($this->user)->patch(action('UserController@updateEmail', [
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
		$this->patch(action('UserController@updateLanguage'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_update_language(): void {
		$this->actingAs($this->user)->patch(action('UserController@updateLanguage', [
			'language' => 'es',
		]))->assertSessionHas('alert',__('profile/page.updatedLanguage',[],$this->user->language));
		$this->assertEquals('es', $this->user->language);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_cant_have_empty_language(): void {

		$this->actingAs($this->user)->patch(action('UserController@updateLanguage', [
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

		$this->actingAs($this->user)->patch(action('UserController@updateLanguage', [
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
		$this->delete(action('UserController@deleteUser'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
		$this->assertNotEquals(0,User::count());
		$this->assertNotEquals(0,Player::count());
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_delete_himself(): void {
		$this->actingAs($this->user)->delete(action('UserController@deleteUser'));
		$this->assertEquals(0,User::count());
		$this->assertEquals(0,Player::count());
	}
}
