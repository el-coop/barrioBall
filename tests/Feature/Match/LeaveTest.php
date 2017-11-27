<?php

namespace Tests\Feature\Match;

use App\Events\Match\PlayerLeft;
use App\Listeners\Match\Cache\ClearPlayersCache;
use App\Listeners\Match\Cache\ClearUserPlayedMatches;
use App\Listeners\Match\SendPlayerLeftNotification;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\PlayerLeft as PlayerLeftNotification;
use Cache;
use Carbon\Carbon;
use Event;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaveTest extends TestCase {
	use RefreshDatabase;

	protected $match;
	protected $manager;


	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create();
		$this->manager = factory(User::class)->create();
		$this->player = factory(User::class)->create();

		$this->match->addManager($this->manager);
	}

	/**
	 * @test
	 * @group match
	 * @group leaveMatch
	 */
	public function test_player_can_leave_match(): void {
		Event::fake();
		$this->match->addPlayer($this->manager);

		$this->actingAs($this->manager)->delete(action('Match\MatchUserController@leaveMatch', $this->match))
			->assertSessionHas('alert', __('match/show.left'));
		Cache::flush();
		$this->assertFalse($this->manager->inMatch($this->match));
		Event::assertDispatched(PlayerLeft::class, function ($event) {
			return $event->user->id == $this->manager->id;
		});

	}

	/**
	 * @test
	 * @group match
	 * @group leaveMatch
	 */
	public function test_cant_leave_ended_match(): void {
		Event::fake();
		$this->match->addPlayer($this->manager);
		$this->match->date_time = Carbon::now()->subDay();
		$this->match->save();

		$this->actingAs($this->manager)
			->delete(action('Match\MatchUserController@leaveMatch', $this->match))
			->assertStatus(302)
		->assertSessionHasErrors('ended');
		Event::assertNotDispatched(PlayerLeft::class, function ($event) {
			return $event->user->id == $this->manager->id;
		});
	}


	/**
	 * @test
	 * @group match
	 * @group leaveMatch
	 */
	public function test_unjoined_cant_leave_match(): void {
		Event::fake();

		$this->actingAs($this->manager)
			->delete(action('Match\MatchUserController@leaveMatch', $this->match))
			->assertStatus(403);
		Event::assertNotDispatched(PlayerLeft::class, function ($event) {
			return $event->user->id == $this->manager->id;
		});
	}


	/**
	 * @test
	 * @group match
	 * @group leaveMatch
	 * @group management
	 */
	public function test_player_left_notification_sent(): void {
		Notification::fake();

		$listener = new SendPlayerLeftNotification;
		$listener->handle(new PlayerLeft($this->match, $this->player));

		Notification::assertSentTo($this->manager, PlayerLeftNotification::class);
	}

	/**
	 * @test
	 * @group match
	 * @group leaveMatch
	 */
	public function test_not_logged_cant_leave_match(): void {
		Event::fake();
		$this->delete(action('Match\MatchUserController@leaveMatch', $this->match))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));

		Event::assertNotDispatched(PlayerLeft::class, function ($event) {
			return $event->user->id == $this->manager->id;
		});
	}


	/**
	 * @test
	 * @group match
	 * @group leaveMatch
	 */
	public function test_clears_users_match_cache_when_user_leaves(): void {
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->username}_nextMatch"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_player"));

		Cache::shouldReceive('tags')->once()->with("{$this->player->username}_played")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');

		$listener = new ClearUserPlayedMatches();
		$listener->handle(new PlayerLeft($this->match, $this->player));
	}

	/**
	 * @test
	 * @group match
	 * @group leaveMatch
	 */
	public function test_clears_match_players_cache_when_user_leaves(): void {
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->match->id}_registeredPlayers"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->match->id}_isFull"));

		$listener = new ClearPlayersCache();
		$listener->handle(new PlayerLeft($this->match, $this->player));
	}
}
