<?php

namespace Tests\Feature\User;

use App\Models\Admin;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfileTest extends TestCase
{
	use DatabaseMigrations;

	public function test_shows_profile_page()
	{
		factory(Admin::class)->create()->each(function($user){
			$user->user()->save(factory(User::class)->make());
		});

		$response = $this->actingAs(User::first())->get(action('UserController@edit'));
		$response->assertStatus(200);
		$response->assertSee("<title>Profile");
	}

}
