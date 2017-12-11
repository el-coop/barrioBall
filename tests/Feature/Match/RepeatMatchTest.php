<?php

namespace Tests\Feature\Match;

use App\Models\Match;
use App\Models\User;
use Cache;
use Carbon\Carbon;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepeatMatchTest extends TestCase {
	use RefreshDatabase;

	protected $match;
	protected $manager;


	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create([
			'date_time' => Carbon::now()->subDay(),
		]);
		$this->manager = factory(User::class)->create();

		$this->match->addManager($this->manager);
	}

	/**
	 * @test
	 * @group match
	 * @group repeatMatch
	 */
	public function test_cant_repeat_match_as_guest(): void {

		$this->patch(action('Match\MatchController@repeatMatch', $this->match), [
			'date' => Carbon::now()->addDays(2),
			'time' => '22:00',
		])->assertRedirect(action('Auth\LoginController@showLoginForm'));

		$this->assertEquals(Carbon::now()->subDay()->format('d/m/y'), Match::first()->date);
	}

	/**
	 * @test
	 * @group match
	 * @group repeatMatch
	 */
	public function test_cant_repeat_match_as_user(): void {

		$user = factory(User::class)->create();

		$this->actingAs($user)->patch(action('Match\MatchController@repeatMatch', $this->match), [
			'date' => Carbon::now()->addDays(2),
			'time' => '22:00',
		])->assertStatus(403);

		$this->assertEquals(Carbon::now()->subDay()->format('d/m/y'), Match::first()->date);
	}


	/**
	 * @test
	 * @group match
	 * @group repeatMatch
	 */
	public function test_cant_repeat_unended_match(): void {
		$this->match->date_time = Carbon::now()->addDays(1);
		$this->match->save();

		$this->actingAs($this->manager)->patch(action('Match\MatchController@repeatMatch', $this->match), [
			'date' => Carbon::now()->addDays(2)->format('d/m/y'),
			'time' => '22:00',
		])->assertStatus(302)
			->assertSessionHasErrors('match');

		$this->assertEquals(Carbon::now()->addDay()->format('d/m/y'), Match::first()->date);
	}


	/**
	 * @test
	 * @group match
	 * @group repeatMatch
	 */
	public function test_match_has_to_be_in_future(): void {
		$this->actingAs($this->manager)->patch(action('Match\MatchController@repeatMatch', $this->match), [
			'date' => Carbon::now()->subDay()->format('d/m/y'),
			'time' => '22:00',
		])->assertStatus(302)
			->assertSessionHasErrors('date');

		$this->assertEquals(Carbon::now()->subDay()->format('d/m/y'), Match::first()->date);
	}

	/**
	 * @test
	 * @group match
	 * @group repeatMatch
	 */
	public function test_validates_input(): void {
		$this->actingAs($this->manager)->patch(action('Match\MatchController@repeatMatch', $this->match))->assertStatus(302)
			->assertSessionHasErrors(['date', 'time']);

		$this->assertEquals(Carbon::now()->subDay()->format('d/m/y'), Match::first()->date);
	}

	/**
	 * @test
	 * @group match
	 * @group repeatMatch
	 */
	public function test_can_repeat_match(): void {

		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->manager->id}_{$this->match->id}_manager"), Mockery::any())->andReturn(true);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("match_{$this->match->id}"), Mockery::any())->andReturn($this->match);
		Cache::shouldReceive('forget')->once()->with(sha1("match_{$this->match->id}"));

		$date = Carbon::now()->addDay();
		$this->actingAs($this->manager)->patch(action('Match\MatchController@repeatMatch', $this->match), [
			'date' => $date->format('d/m/y'),
			'time' => '22:00',
		])->assertStatus(302)->assertSessionHas('alert');

		$this->assertEquals($date->format('d/m/y'), Match::first()->date);
	}
}
