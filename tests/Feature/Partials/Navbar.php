<?php

namespace Tests\Feature\Partials;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Navbar extends TestCase {

	use RefreshDatabase;


	/**
	 * @test
	 * @group global
	 * @group navbar
	 */
	public function test_shows_unautorized_navbar_unlogged_in(): void {
		$this->get(action('Match\MatchController@showSearch'))
			->assertSee('<a class="nav-link " href="' . action('Auth\LoginController@showLoginForm'). '">');
	}

	/**
	 * @test
	 * @group global
	 * @group navbar
	 */
	public function test_shows_autorized_navbar_logged_in(): void {
		$user = factory(User::class)->create();

		$this->actingAs($user)->get(action('Match\MatchController@showSearch'))
			->assertSee('<form method="post" action="' . action("Auth\LoginController@logout") . '">');
	}
}
