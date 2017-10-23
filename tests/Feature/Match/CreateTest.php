<?php

namespace Tests\Feature\Match;

use App\Models\Match;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTest extends TestCase {
	use RefreshDatabase;

	protected $user;

	public function setUp() {
		parent::setUp();
		$this->user = factory(User::class)->create();
	}

	/**
	 * @test
	 * @group create
	 * @group match
	 */
	public function test_not_logged_in_cant_see_create_page(): void {
		$this->get(action('Match\MatchController@showCreate'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group create
	 * @group match
	 */
	public function test_can_see_page_logged_in(): void {
		$this->actingAs($this->user)->get(action('Match\MatchController@showCreate'))
			->assertStatus(200)
			->assertSee('<title>' . __('navbar.createLink'));
	}

	/**
	 * @test
	 * @group create
	 * @group match
	 */
	public function test_not_logged_in_cant_create_match(): void {
		$this->post(action('Match\MatchController@create'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group create
	 * @group match
	 */
	public function test_can_create_match_and_is_set_as_manager(): void {
		$time = Carbon::now();
		$this->actingAs($this->user)->post(action('Match\MatchController@create'), [
			'name' => 'Nurs Match',
			'address' => 'Test Test',
			'lat' => 0,
			'lng' => 0,
			'players' => 8,
			'date' =>$time->addDays(1)->format('d/m/y'),
			'time' => $time->format('H:i'),
			'description' => 'description',
		])->assertRedirect(action('Match\MatchController@showMatch', Match::first()));
		$this->assertArraySubset([
			'name' => 'Nurs Match',
			'address' => 'Test Test',
		], Match::first()->toArray());
		$this->assertEquals($time->format('d/m/y H:i'),Match::first()->date_time->format('d/m/y H:i'));
	}

	/**
	 * @test
	 * @group create
	 * @group match
	 */
	public function test_validates_input(): void {
		$this->actingAs($this->user)->post(action('Match\MatchController@create'), [
			'name' => 'N',
			'address' => 'T',
			'description' => 'd',
		])->assertSessionHasErrors([
			'name', 'address', 'lat', 'lng', 'players', 'date', 'time', 'description',
		]);

	}
}
