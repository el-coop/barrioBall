<?php

namespace Tests\Feature\Match;

use App\Models\Match;
use Faker\Generator;
use Faker\Provider\Base;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase {
	use RefreshDatabase;

	/**
	 * @test
	 * @group match
	 * @group searchMatch
	 */
	public function test_shows_search_page(): void {
		$this->get(action('Match\MatchController@showSearch'))
			->assertStatus(200)
			->assertSee("<title>" . __('navbar.searchLink'));
	}

	/**
	 * @test
	 * @group match
	 * @group searchMatch
	 */
	public function test_returns_erros_on_input_errors(): void {

		$this->post(action('Match\MatchController@search'), [
			'date' => '',
			'from' => '',
			'to' => '',
			'north' => '',
			'east' => '',
			'west' => '',
			'south' => '',
		])->assertSessionHasErrors([
			'date',
			'from',
			'to',
			'north',
			'east',
			'west',
			'south',
		]);


		$this->post(action('Match\MatchController@search'), [
			'date' => 'notAdate',
			'from' => 'notAnHour',
			'to' => 'notAnHour',
			'north' => 'notANumber',
			'east' => 'notANumber',
			'west' => 'notANumber',
			'south' => 'notANumber',
		])->assertSessionHasErrors([
			'date',
			'from',
			'to',
			'north',
			'east',
			'west',
			'south',
		]);
	}

	/**
	 * @test
	 * @group match
	 * @group searchMatch
	 */
	public function test_empty_to_no_matches_found(): void {
		$this->post(action('Match\MatchController@search'), [
			'date' => '17/05/17',
			'from' => '10:30',
			'to' => '12:30',
			'north' => '1',
			'east' => '1',
			'west' => '0',
			'south' => '0',
		])->assertStatus(200)
			->assertJsonFragment([
				'data' => [],
				'total' => 0,
			]);
	}

	/**
	 * @test
	 * @group match
	 * @group searchMatch
	 */
	public function test_matches_are_returned(): void {

		$faker = new Base(new Generator());

		$matches = factory(Match::class, 5)->create([
			'date' => date('y/m/d'),
			'lat' => $faker->randomFloat(15, 0, 5),
			'lng' => $faker->randomFloat(15, 0, 5),
		]);

		$response = $this->post(action('Match\MatchController@search'), [
			'date' => date('d/m/y'),
			'from' => '00:01',
			'to' => '23:59',
			'north' => 5,
			'east' => 5,
			'west' => 0,
			'south' => 0,
		])->assertStatus(200)
			->assertJson([
				'total' => 5,
			]);

		foreach ($matches as $match) {
			$response->assertJsonFragment([
				'name' => $match->name,
			]);
		}
	}
}
