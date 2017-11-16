<?php

namespace Tests\Feature\Pages;

use App\Models\Match;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardPageTest extends TestCase
{
	use RefreshDatabase;

	protected $player;

	public function setUp() {
		parent::setUp();
		$this->player = factory(User::class)->create();
	}

	/**
	 * @test
	 * @group global
	 * @group dashboard
	 */
	public function test_not_logged_in_cant_see(): void {
		$this->get(action('HomeController@index'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group global
	 * @group dashboard
	 */
	public function test_logged_in_can_see(): void {
		$this->actingAs($this->player)->get(action('HomeController@index'))
			->assertStatus(200);
	}


	/**
	 * @test
	 * @group global
	 * @group dashboard
	 */
	public function test_sees_no_matches_alert_for_no_matches(): void {
		$this->actingAs($this->player)->get(action('HomeController@index'))
			->assertSee(__('global/dashboard.noUpcomingMatches',[],$this->player->language));
	}


	/**
	 * @test
	 * @group global
	 * @group dashboard
	 */
	public function test_sees_upcoming_matches_alert_with_time(): void {
		$match = factory(Match::class)->create([
			'date_time' => Carbon::now()->addDay()
		]);
		$match->addPlayer($this->player);
		Carbon::setLocale($this->player->language);

		$this->actingAs($this->player)->get(action('HomeController@index'))
			->assertSee(__('global/dashboard.upcomingMatch',[
				'name' => $match->name,
				'url' => $match->url
			],$this->player->language))->assertSee($match->date_time->diffForHumans());
	}

	/**
	 * @test
	 * @group global
	 * @group dashboard
	 */
	public function test_sees_no_request_alert_for_no_requests(): void {
		$this->actingAs($this->player)->get(action('HomeController@index'))
			->assertSee(__('global/dashboard.noRequests',[],$this->player->language));
	}

	/**
	 * @test
	 * @group global
	 * @group dashboard
	 */
	public function test_sees_request_alert_for_requests(): void {
		$match = factory(Match::class)->create([
			'date_time' => Carbon::now()->addDay()
		]);
		$match->addManager($this->player);
		$otherPlayer = factory(User::class)->create();
		$match->addJoinRequest($otherPlayer);

		$this->actingAs($this->player)->get(action('HomeController@index'))
			->assertSee(__('global/dashboard.pendingRequest',[],$this->player->language));
	}
}
