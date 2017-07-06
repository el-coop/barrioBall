<?php

namespace Tests\Browser\Match;

use App\Models\Match;
use Faker\Generator;
use Faker\Provider\Base;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SearchTest extends DuskTestCase {
	use DatabaseMigrations;

	/**
	 * A Dusk test example.
	 *
	 * @return void
	 */
	public function test_shows_search_errors() {
		$this->browse(function (Browser $browser) {
			$browser->visit(action('Match\MatchController@showSearch'))
				->waitFor('.leaflet-map-pane', 20)
				->clear('date')
				->clear('from')
				->clear('to')
				->click('.sm-btn-block')
				->assertSee('* The date field is required.')
				->assertSee('* The from field is required.')
				->assertSee('* The to field is required.');
		});
	}

	/**
	 * @group current
	 */

	public function test_shows_search_results(){
		$this->browse(function (Browser $browser) {

			$faker = new Base(new Generator());

			$matches = factory(Match::class,5)->create([
				'date' => date('y/m/d'),
				'lat' => $faker->randomFloat(15, 0, 5),
				'lng' => $faker->randomFloat(15, 0, 5),
			]);

			$browser->visit(action('Match\MatchController@showSearch'))
				->waitFor('.leaflet-map-pane', 20)
				->type('from','00:01')
				->type('to','23:59')
				->click('.sm-btn-block');

			$i = 0;
			foreach ($matches as $match){
				$browser->assertSee($match->name);
				$browser->assertVisible(".icon-result-{$i}");
				$i++;
			}
		});

	}
}
