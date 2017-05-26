<?php

namespace Tests\Feature\Match;

use App\Models\Match;
use Carbon\Carbon;
use Faker\Generator;
use Faker\Provider\Base;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchTest extends TestCase
{
	use DatabaseMigrations;

    public function test_shows_search_page()
    {
    	$response = $this->get(action('Match\MatchController@showSearch'));
    	$response->assertStatus(200);
		$response->assertSee("<title>Search Matches");
    }

	public function test_returns_erros_on_input_errors(){

		$response = $this->post(action('Match\MatchController@search'),[
			'date' => '',
			'from' => '',
			'to' => '',
			'north' => '',
			'east' => '',
			'west' => '',
			'south' => ''
		]);

		$response->assertStatus(302);
		$response->assertSessionHasErrors([
			'date',
			'from',
			'to',
			'north',
			'east',
			'west',
			'south'
		]);


		$response = $this->post(action('Match\MatchController@search'),[
			'date' => 'notAdate',
			'from' => 'notAnHour',
			'to' => 'notAnHour',
			'north' => 'notANumber',
			'east' => 'notANumber',
			'west' => 'notANumber',
			'south' => 'notANumber'
		]);

		$response->assertStatus(302);

		$response->assertSessionHasErrors([
			'date',
			'from',
			'to',
			'north',
			'east',
			'west',
			'south'
		]);
	}

	public function test_empty_to_no_matches_found(){
		$response = $this->post(action('Match\MatchController@search'),[
			'date' => '17/05/17',
			'from' => '10:30',
			'to' => '12:30',
			'north' => '1',
			'east' => '1',
			'west' => '0',
			'south' => '0'
		]);

		$response->assertStatus(200);
		$response->assertJsonFragment([
			'data' => [],
			'total' => 0
		]);
	}

	public function test_matches_are_returned(){

		$faker = new Base(new Generator());

		$matches = factory(Match::class,5)->create([
			'date' => date('y/m/d'),
			'lat' => $faker->randomFloat(15, 0, 5),
			'lng' => $faker->randomFloat(15, 0, 5),
		]);

		$response = $this->post(action('Match\MatchController@search'),[
			'date' => date('d/m/y'),
			'from' => '00:01',
			'to' => '23:59',
			'north' => 5,
			'east' => 5,
			'west' => 0,
			'south' => 0
		]);

		$response->assertStatus(200);

		$response->assertJson([
			'total' => 5
		]);

		foreach ($matches as $match){
			$response->assertJsonFragment([
				'name' => $match->name
			]);
		}
	}
}
