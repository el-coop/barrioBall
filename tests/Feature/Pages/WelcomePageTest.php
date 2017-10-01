<?php

namespace Tests\Feature\Pages;

use App\Models\Admin;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomePageTest extends TestCase
{
	use RefreshDatabase;

	public function test_shows_not_logged_login_register_buttons()
	{
		$this->get(action('HomeController@welcome'))
			->assertStatus(200)
			->assertSee('<a href="' . action('Auth\LoginController@showLoginForm') . '" class="btn btn-primary btn-block btn-lg">')
			->assertSee('<a href="' . action('Auth\RegisterController@showRegistrationForm') .'" class="btn btn-outline-dark btn-block btn-lg">');
	}

	public function test_shows_logged_in_correct_buttons()
	{
		factory(Admin::class)->create()->each(function($user){
			$user->user()->save(factory(User::class)->make());
		});

		$this->actingAs(User::first())->get(action('HomeController@welcome'))
			->assertStatus(200)
			->assertSee('<a href="' . action('Match\MatchController@showCreate') . '" class="btn btn-info btn-block btn-lg">')
			->assertSee('<a href="' . action('UserController@show') . '" class="btn btn-outline-dark btn-block btn-lg">')
			->assertSee('<a href="' . action('Match\MatchController@showSearch') . '" class="btn btn-primary btn-block btn-lg">');
	}
}
