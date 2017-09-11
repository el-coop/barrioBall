<?php

namespace Tests\Feature\User;

use App\Models\Admin;
use App\Models\User;
use DB;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
	use RefreshDatabase;

	protected $user;

	protected function setResetToken(){
		$hash = hash_hmac('sha256', Str::random(40), $this->user);
		DB::table('password_resets')->insert([
			'email' => $this->user->email,
			'token' => bcrypt($hash)
		]);
		return $hash;
	}

	protected function makeUser(){
		factory(Admin::class)->create()->each(function($user){
			$user->user()->save(factory(User::class)->make());
		});

		return User::first();
	}

	public function setUp() {
		parent::setUp();
		$this->user = $this->makeUser();
	}

	public function test_shows_password_reset()
    {
		$response = $this->get(action('Auth\ForgotPasswordController@showLinkRequestForm'));
		$response->assertStatus(200);
		$response->assertSee('<title>Reset Password');
    }

    public function test_send_reset_email(){
		Notification::fake();

		$response = $this->post(action('Auth\ForgotPasswordController@sendResetLinkEmail'),[
			'email' => $this->user->email
		]);

		Notification::assertSentTo($this->user, ResetPassword::class);
		$this->assertDatabaseHas('password_resets',[
			'email' => $this->user->email
		]);
	}

	public function test_shows_reset_page(){

    	$hash = $this->setResetToken();

		$response = $this->get(action('Auth\ResetPasswordController@showResetForm',$hash));
		$response->assertStatus(200);
		$response->assertSee('<title>Reset Password');
	}

	public function test_resets_password(){

		$hash = $this->setResetToken();

		$response =$this->post(action('Auth\ResetPasswordController@reset'),[
			'email' => $this->user->email,
			'token' => $hash,
			'password' => '123456',
			'password_confirmation' => '123456'
		]);

		$response->assertSessionHas('status','Your password has been reset!');
		$response->assertRedirect(action('HomeController@index'));
	}
}
