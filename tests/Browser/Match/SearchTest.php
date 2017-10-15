<?php

namespace Tests\Browser\Match;

use App\Models\Match;
use Carbon\Carbon;
use Faker\Generator;
use Faker\Provider\Base;
use Tests\Browser\Pages\Match\SearchPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SearchTest extends DuskTestCase {
	use DatabaseMigrations;

	/**
	 * @test
	 * @group searchMatch
	 * @group match
	 */
	public function test_shows_search_errors() {
		$this->browse(function (Browser $browser) {
			$browser->visit(new SearchPage)
				->waitFor('@map')
				->submitForm([
					'date' => '',
					'from' => '',
					'to' => ''
				])
				->waitFor('.invalid-feedback')
				->assertSee('* The date field is required.')
				->assertSee('* The from field is required.')
				->assertSee('* The to field is required.');
		});
	}

	/**
	 * @test
	 * @group searchMatch
	 * @group match
	 */
	public function test_shows_search_results(): void {
		$faker = new Base(new Generator());

		$matches = factory(Match::class, 5)->create([
			'date' => (new Carbon())->addDay()->format('y/m/d'),
			'lat' => $faker->randomFloat(15, 0, 5),
			'lng' => $faker->randomFloat(15, 0, 5),
		]);

		$this->browse(function (Browser $browser) use($matches) {
			$browser->visit(new SearchPage)
				->waitFor('@map')
				->submitForm([
					'date' => Carbon::now()->addDay()->format('d/m/y'),
					'from' => '00:01',
					'to' => '23:59'
				])
				->waitFor('@search-results')
				->assertSeeMatches($matches);
		});

	}

	/**
	 * @test
	 * @group searchMatch
	 * @group match
	 */
	public function test_click_marker_highlights_result(): void {
		$faker = new Base(new Generator());

		$matches = factory(Match::class, 5)->create([
			'date' => (new Carbon())->addDay()->format('y/m/d'),
			'lat' => $faker->randomFloat(15, 0, 5),
			'lng' => $faker->randomFloat(15, 0, 5),
		]);

		$this->browse(function (Browser $browser) use ($matches) {

			$browser->visit(new SearchPage)
				->waitFor('@map')
				->submitForm([
					'date' => Carbon::now()->addDay()->format('d/m/y'),
					'from' => '00:01',
					'to' => '23:59'
				])
				->waitFor('@search-results')
				->click('.icon-result-4')
				->assertSeeIn('.card.selected > .card-header',$matches->last()->name);
		});

	}

	/**
	 * @test
	 * @group searchMatch
	 * @group match
	 */
	public function test_switches_between_map_and_form_on_small_screen(): void {
		$this->browse(function (Browser $browser) {
			$browser->resize(320, 640)
				->visit(new SearchPage)
				->assertVisible('@front-shown')
				->click('@flip-button')
				->assertVisible('@back-shown')
				->click('@flip-button')
				->assertVisible('@front-shown');
		});
	}
}
