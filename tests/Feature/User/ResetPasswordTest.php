<?php

namespace Tests\Feature\User;

use App\Models\Admin;
use App\Models\User;
use App\Notifications\User\ResetPassword;
use DB;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase {
	use RefreshDatabase;

	protected $user;

	public function setUp() {
		parent::setUp();
		$this->user = factory(User::class)->create();
	}

	/**
	 * @test
	 * @group user
	 * @group resetPassword
	 */
	public function test_shows_password_reset(): void {
		$this->get(action('Auth\ForgotPasswordController@showLinkRequestForm'))
			->assertStatus(200)
			->assertSee(__('auth.resetPassword', [], $this->user->language));
	}

	/**
	 * @test
	 * @group user
	 * @group resetPassword
	 */
	public function test_doesnt_shows_password_reset_to_logged_in(): void {
		$this->actingAs($this->user)->get(action('Auth\ForgotPasswordController@showLinkRequestForm'))
			->assertRedirect(action('HomeController@index'));
	}

	/**
	 * @test
	 * @group user
	 * @group resetPassword
	 */
	public function test_send_reset_email(): void {
		Notification::fake();

		$this->post(action('Auth\ForgotPasswordController@sendResetLinkEmail'), [
			'email' => $this->user->email,
		])->assertSessionHas('status', __('passwords.sent'));

		$this->assertDatabaseHas('password_resets', [
			'email' => $this->user->email,
		]);

		Notification::assertSentTo($this->user, ResetPassword::class);
	}

	/**
	 * @test
	 * @group user
	 * @group resetPassword
	 */
	public function test_doesnt_send_reset_email_to_logged(): void {
		Notification::fake();

		$this->actingAs($this->user)->post(action('Auth\ForgotPasswordController@sendResetLinkEmail'), [
			'email' => $this->user->email,
		])->assertRedirect(action('HomeController@index'));

		$this->assertDatabaseMissing('password_resets', [
			'email' => $this->user->email,
		]);

		Notification::assertNotSentTo($this->user, ResetPassword::class);
	}

	/**
	 * @test
	 * @group user
	 * @group resetPassword
	 */
	public function test_doesnt_send_reset_email_to_non_Existent_email(): void {
		Notification::fake();

		$this->post(action('Auth\ForgotPasswordController@sendResetLinkEmail'), [
			'email' => 'dla@bla.com',
		])->assertSessionHasErrors('email', __('passwords.user'));

		$this->assertDatabaseMissing('password_resets', [
			'email' => $this->user->email,
		]);

		Notification::assertNotSentTo($this->user, ResetPassword::class);
	}

	/**
	 * @test
	 * @group user
	 * @group resetPassword
	 */
	public function test_doesnt_send_reset_email_to_non_email(): void {
		Notification::fake();

		$this->post(action('Auth\ForgotPasswordController@sendResetLinkEmail'), [
			'email' => 'dla',
		])->assertSessionHasErrors('email');

		$this->assertDatabaseMissing('password_resets', [
			'email' => $this->user->email,
		]);

		Notification::assertNotSentTo($this->user, ResetPassword::class);
	}


	/**
	 * @test
	 * @group user
	 * @group resetPassword
	 */
	public function test_shows_reset_page(): void {

		$hash = $this->setResetToken();
		$this->get(action('Auth\ResetPasswordController@showResetForm', $hash))
			->assertStatus(200)
			->assertSee(__('auth.resetPassword'));
	}

	/**
	 * @return string
	 */
	protected function setResetToken(): string {
		$hash = hash_hmac('sha256', Str::random(40), $this->user);
		DB::table('password_resets')->insert([
			'email' => $this->user->email,
			'token' => bcrypt($hash),
		]);

		return $hash;
	}

	/**
	 * @test
	 * @group user
	 * @group resetPassword
	 */
	public function doesnt_show_reset_page_for_logged_in(): void {

		$hash = $this->setResetToken();
		$this->actingAs($this->user)->get(action('Auth\ResetPasswordController@showResetForm', $hash))
			->assertRedirect(action('HomeController@index'));
	}

	/**
	 * @test
	 * @group user
	 * @group resetPassword
	 */
	public function test_resets_password(): void {

		$hash = $this->setResetToken();

		$response = $this->post(action('Auth\ResetPasswordController@reset'), [
			'email' => $this->user->email,
			'token' => $hash,
			'password' => '123456',
			'password_confirmation' => '123456',
		]);

		$response->assertSessionHas('status', __('passwords.reset'));
		$response->assertRedirect(action('HomeController@index'));
		$this->assertTrue(Hash::check('123456', User::first()->password));
	}

	/**
	 * @test
	 * @group user
	 * @group resetPassword
	 */
	public function test_doesnt_resets_password_for_logged_in(): void {

		$hash = $this->setResetToken();

		$response = $this->actingAs($this->user)->post(action('Auth\ResetPasswordController@reset'), [
			'email' => $this->user->email,
			'token' => $hash,
			'password' => '123456',
			'password_confirmation' => '123456',
		]);

		$response->assertRedirect(action('HomeController@index'));
		$this->assertFalse(Hash::check('123456', User::first()->password));
	}


}
