<?php

namespace Tests\Browser\Match;

use App\Models\Admin;
use App\Models\Match;
use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShowTest extends DuskTestCase
{
	use DatabaseMigrations;

	protected $match;

	protected function setMatch(){
		factory(Admin::class)->create()->each(function($user){
			$user->user()->save(factory(User::class)->make());
		});

		factory(Match::class)->create()->each(function($match){
			$match->addManager(User::first());
		});

		return Match::first();
	}

	public function setUp() {
		parent::setUp();
		$this->match = $this->setMatch();
	}

	public function test_shows_map_with_icon()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit(action('Match\MatchController@showMatch', $this->match))
				->waitFor('.leaflet-marker-icon', 20)
				->assertVisible('.leaflet-marker-icon');
		});
	}


	public function test_shows_modal_on_click()
	{
		$this->browse(function (Browser $browser) {
			$browser->loginAs(User::first())->visit(action('Match\MatchController@showMatch', $this->match))
				->waitFor('.btn-info')
				->click('.btn-info')
				->waitFor('.v-select',2)
				->assertVisible('.v-select');
		});
	}
}
