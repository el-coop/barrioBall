<?php

namespace Tests\Feature\Partials;

use App\Models\Admin;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Navbar extends TestCase
{

 	use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
	public function test_shows_unautorized_navbar_unlogged_in()
	{
		$response = $this->get(action('Match\MatchController@showSearch'));

		$response->assertSee("<a class=\"nav-link active\" href=\"". action('Match\MatchController@showSearch') . "\">");
	}


	public function test_shows_autorized_navbar_logged_in()
	{
		factory(Admin::class)->create()->each(function($user){
			$user->user()->save(factory(User::class)->make());
		});


		$response = $this->actingAs(User::first())->get(action('Match\MatchController@showSearch'));

		$response->assertSee("<form method=\"post\" action=\"" . action("Auth\LoginController@logout") . "\">");
	}
}
