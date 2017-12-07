<?php

namespace Tests\Feature\Match;

use App\Events\Match\Created;
use App\Listeners\Admin\Cache\ClearMatchOverviewCache;
use App\Listeners\Match\Cache\ClearUserManagedMatches;
use App\Models\Match;
use App\Models\User;
use Cache;
use Carbon\Carbon;
use Event;
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
		Event::fake();
		$time = Carbon::now();
		$this->actingAs($this->user)->post(action('Match\MatchController@create'), [
			'name' => 'Nurs Match',
			'address' => 'Test Test',
			'lat' => 0,
			'lng' => 0,
			'players' => 8,
			'date' => $time->addDay()->format('d/m/y'),
			'time' => $time->format('H:i'),
			'description' => 'description',
		])->assertRedirect(Match::first()->url);
		$this->assertArraySubset([
			'name' => 'Nurs Match',
			'address' => 'Test Test',
		], Match::first()->toArray());
		$this->assertEquals($time->format('d/m/y H:i'), Match::first()->date_time->format('d/m/y H:i'));

		Event::assertDispatched(Created::class);
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
		])->assertStatus(302)->assertSessionHasErrors([
			'name', 'address', 'lat', 'lng', 'players', 'date', 'time', 'description',
		]);
		$this->assertEquals(0, Match::count());

	}


	/**
	 * @test
	 * @group match
	 * @group create
	 */
	public function test_match_has_to_be_in_future(): void {
		$this->actingAs($this->user)->post(action('Match\MatchController@create'), [
			'name' => 'Nurs Match',
			'address' => 'Test Test',
			'lat' => 0,
			'lng' => 0,
			'players' => 8,
			'date' => Carbon::now()->subDay()->format('d/m/y'),
			'time' => '22:00',
			'description' => 'description',

		])->assertStatus(302)
			->assertSessionHasErrors('date');

		$this->assertEquals(0, Match::count());
	}

	/**
	 * @test
	 * @group match
	 * @group create
	 */
	public function test_clears_users_match_cache_when_match_created(): void {
		$match = factory(Match::class)->create();
		$match->addManager($this->user);

		Cache::shouldReceive('tags')->once()->with("{$this->user->username}_managed")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');

		Cache::shouldReceive('forget')->once()->with(sha1("{$this->user->username}_hasManagedMatches"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->user->id}_{$match->id}_manager"));

		$listener = new ClearUserManagedMatches;
		$listener->handle(new Created($match));
	}

	/**
	 * @test
	 * @group match
	 * @group deleteMatch
	 * @group overviewPage
	 */
	public function test_admin_match_cache_cleared_when_matchDeleted_dispatched(): void {
		$match = factory(Match::class)->create();

		Cache::shouldReceive('tags')->once()->with("admin_matches")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');

		$listener = new ClearMatchOverviewCache;
		$listener->handle(new Created($match));
	}
}
