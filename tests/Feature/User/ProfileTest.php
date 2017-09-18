<?php

namespace Tests\Feature\User;

use App\Models\Admin;
use App\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase {
	use RefreshDatabase;

	public function setUp() {
		parent::setUp();
		factory(Admin::class)->create()->each(function ($user) {
			$user->user()->save(factory(User::class)->make([
				'language' => 'en'
			]));
		});
	}

	public function test_doesnt_show_profile_page_unauthorized() {
		$response = $this->get(action('UserController@show'));
		$response->assertStatus(302);
		$response->assertRedirect(action('Auth\LoginController@showLoginForm'));

	}

	public function test_shows_profile_page() {
		$response = $this->actingAs(User::first())->get(action('UserController@show'));
		$response->assertStatus(200);
		$response->assertSee("<title>Profile");
	}

	public function test_unlogged_cant_update_username() {
		$response = $this->patch(action('UserController@updateUsername'));
		$response->assertStatus(302);
		$response->assertRedirect(action('Auth\LoginController@showLoginForm'));

	}


	public function test_user_can_update_username() {

		$response = $this->actingAs(User::first())->patch(action('UserController@updateUsername', [
			'username' => 'newUsername'
		]));
		$response->assertStatus(302);
		$response->assertSessionHas('alert');
		$this->assertDatabaseHas('users', [
			'username' => 'newUsername'
		]);
	}

	public function test_user_cant_have_empty_username() {

		$response = $this->actingAs(User::first())->patch(action('UserController@updateUsername', [
			'username' => ''
		]));
		$response->assertStatus(302);
		$response->assertSessionHasErrors('username');
		$this->assertDatabaseMissing('users', [
			'username' => ''
		]);
	}

	public function test_unlogged_cant_update_password() {
		$response = $this->patch(action('UserController@updatePassword'));
		$response->assertStatus(302);
		$response->assertRedirect(action('Auth\LoginController@showLoginForm'));

	}


	public function test_user_can_update_password() {

		$response = $this->actingAs(User::first())->patch(action('UserController@updatePassword', [
			'password' => '12345678',
			'password_confirmation' => '12345678'
		]));
		$response->assertStatus(302);
		$response->assertSessionHas('alert');
		$this->assertTrue(Hash::check('12345678', User::first()->password));
	}

	public function test_user_cant_have_empty_password() {

		$response = $this->actingAs(User::first())->patch(action('UserController@updatePassword', [
			'password' => '',
			'password_confirm' => ''
		]));
		$response->assertStatus(302);
		$response->assertSessionHasErrors('password');
	}

	public function test_user_cant_have_unconfirmed_password() {

		$response = $this->actingAs(User::first())->patch(action('UserController@updatePassword', [
			'password' => '1234567',
			'password_confirm' => '123'
		]));
		$response->assertStatus(302);
		$response->assertSessionHasErrors('password');
	}

	public function test_unlogged_cant_update_email() {
		$response = $this->patch(action('UserController@updateEmail'));
		$response->assertStatus(302);
		$response->assertRedirect(action('Auth\LoginController@showLoginForm'));

	}

	public function test_user_can_update_email() {

		$response = $this->actingAs(User::first())->patch(action('UserController@updateEmail', [
			'email' => 'new@new.new',
		]));
		$response->assertStatus(302);
		$response->assertSessionHas('alert');
		$this->assertDatabaseHas('users', [
			'email' => 'new@new.new'
		]);
	}

	public function test_user_cant_have_empty_email() {

		$response = $this->actingAs(User::first())->patch(action('UserController@updateEmail', [
			'email' => '',
		]));
		$response->assertStatus(302);
		$response->assertSessionHasErrors('email');
	}

	public function test_user_cant_have_non_email() {

		$response = $this->actingAs(User::first())->patch(action('UserController@updateEmail', [
			'email' => 'test',
		]));
		$response->assertStatus(302);
		$response->assertSessionHasErrors('email');
		$this->assertDatabaseMissing('users', [
			'email' => 'test'
		]);
	}

	public function test_unlogged_cant_update_language() {
		$response = $this->patch(action('UserController@updateLanguage'));
		$response->assertStatus(302);
		$response->assertRedirect(action('Auth\LoginController@showLoginForm'));

	}

	public function test_user_can_update_language() {

		$response = $this->actingAs(User::first())->patch(action('UserController@updateLanguage', [
			'language' => 'es',
		]));
		$response->assertStatus(302);
		$response->assertSessionHas('alert');
		$this->assertDatabaseHas('users', [
			'language' => 'es'
		]);
	}

	public function test_user_cant_have_empty_language() {

		$response = $this->actingAs(User::first())->patch(action('UserController@updateLanguage', [
			'language' => '',
		]));
		$response->assertStatus(302);
		$response->assertSessionHasErrors('language');
	}

	public function test_user_cant_have_non_language() {

		$response = $this->actingAs(User::first())->patch(action('UserController@updateLanguage', [
			'language' => 'test',
		]));
		$response->assertStatus(302);
		$response->assertSessionHasErrors('language');
		$this->assertDatabaseMissing('users', [
			'language' => 'test'
		]);
	}

	public function test_unlogged_cant_delete_user() {
		$response = $this->delete(action('UserController@deleteUser'));
		$response->assertStatus(302);
		$response->assertRedirect(action('Auth\LoginController@showLoginForm'));

	}

	public function test_user_can_delete_himself() {

		$username = User::first()->username;

		$response = $this->actingAs(User::first())->delete(action('UserController@deleteUser'));
		$response->assertStatus(302);
		$this->assertDatabaseMissing('users', [
			'username' => $username
		]);
	}
}
