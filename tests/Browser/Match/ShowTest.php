<?php

namespace Tests\Browser\Match;

use App\Models\Admin;
use App\Models\Match;
use App\Models\User;
use Tests\Browser\Pages\Match\ShowPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShowTest extends DuskTestCase {
	use DatabaseMigrations;

	protected $match;

	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create();
	}

	public function test_shows_map_with_icon() {
		$this->browse(function (Browser $browser) {
			$browser->visit(new ShowPage($this->match))
				->waitFor('.leaflet-marker-icon')
				->assertVisible('.leaflet-marker-icon');
		});
	}
}
