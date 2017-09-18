<?php

namespace Tests\Browser\User;

use App\Models\Admin;
use App\Models\Match;
use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileTest extends DuskTestCase
{
	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();
		factory(Admin::class)->create()->each(function ($user) {
			$user->user()->save(factory(User::class)->make([
				'language' => 'en'
			]));
		});
	}

	public function test_shows_user_played_matches()
	{
		$this->browse(function (Browser $browser) {
			$matches = factory(Match::class,5)->create()->each(function($match){
				$match->addPlayer(User::first());
			});

			$browser->loginAs(User::first())->visit(action('UserController@show'))
				->waitFor('.btn.btn-dark');

			foreach ($matches as $match){
				$browser->assertSee($match->name);
			}
		});
	}

	public function test_shows_user_managed_matches()
	{
		$this->browse(function (Browser $browser) {
			$matches = factory(Match::class,5)->create()->each(function($match){
				$match->addManager(User::first());
			});

			$browser->loginAs(User::first())->visit(action('UserController@show'))
				->waitFor('.btn.btn-dark');

			foreach ($matches as $match){
				$browser->assertSee($match->name);
			}
		});
	}
}
