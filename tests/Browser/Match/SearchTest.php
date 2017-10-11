<?php

namespace Tests\Browser\Match;

use App\Models\Match;
use Carbon\Carbon;
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
				->waitFor('.invalid-feedback',2)
				->assertSee('* The date field is required.')
				->assertSee('* The from field is required.')
				->assertSee('* The to field is required.');
		});
	}

	public function test_shows_search_results() {
		$this->browse(function (Browser $browser) {

			$faker = new Base(new Generator());

			$matches = factory(Match::class, 5)->create([
				'date' => (new Carbon())->addDay()->format('y/m/d'),
				'lat' => $faker->randomFloat(15, 0, 5),
				'lng' => $faker->randomFloat(15, 0, 5),
			]);

			$browser->visit(action('Match\MatchController@showSearch'))
				->waitFor('.leaflet-map-pane', 20)
				->type('date', (new Carbon())->addDay()->format('d/m/y'))
				->type('from', '00:01')
				->type('to', '23:59')
				->click('.sm-btn-block')
				->waitFor('.card-header',2);

			$i = 0;
			foreach ($matches as $match) {
				$browser->assertSee($match->name);
				$browser->assertVisible(".icon-result-{$i}");
				$i++;
			}
		});

	}


	public function test_click_marker_highlights_result() {
		$this->browse(function (Browser $browser) {

			$faker = new Base(new Generator());

			$matches = factory(Match::class, 5)->create([
				'date' => (new Carbon())->addDay()->format('y/m/d'),
				'lat' => $faker->randomFloat(15, 0, 5),
				'lng' => $faker->randomFloat(15, 0, 5),
			]);

			$browser->visit(action('Match\MatchController@showSearch'))
				->waitFor('.leaflet-map-pane', 20)
				->type('date', (new Carbon())->addDay()->format('d/m/y'))
				->type('from', '00:01')
				->type('to', '23:59')
				->click('.sm-btn-block')
				->waitFor('.icon-result-4')
				->click('.icon-result-4')
				->assertSeeIn('.card.selected > .card-header',$matches->last()->name);
		});

	}

	public function test_switches_between_map_and_form_on_small_screen() {
		$this->browse(function (Browser $browser) {
			$browser->resize(320, 640)
				->visit(action('Match\MatchController@showSearch'))
				->assertVisible('.flipper:not(.flipped)')
				->click('.btn.btn-primary')
				->assertVisible('.flipper.flipped')
				->click('.btn.btn-primary')
				->assertVisible('.flipper:not(.flipped)');

		});
	}
}
