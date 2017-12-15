<?php

namespace Tests\Feature\Match;

use App\Events\Match\PlayerRemoved;
use App\Listeners\Match\Cache\ClearPlayersCache;
use App\Listeners\Match\Cache\ClearUserPlayedMatches;
use App\Listeners\Match\SendPlayerRemovedNotification;
use App\Models\Admin;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\PlayerRemoved as PlayerRemovedNotification;
use Cache;
use Carbon\Carbon;
use Event;
use Mockery;
use Mockery\Mock;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemovePlayerTest extends TestCase {
	use RefreshDatabase;

	protected $match;
	protected $admin;
	protected $player;

	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create();
		$this->admin = factory(User::class)->create([
			'user_id' => function () {
				return factory(Admin::class)->create()->id;
			},
			'user_type' => 'Admin',
		]);
		$this->player = factory(User::class)->create();
		$this->match->addManager($this->admin);
		$this->match->addPlayer($this->admin);
		$this->match->addPlayer($this->player);
	}

	/**
	 * @test
	 * @group match
	 * @group removePlayer
	 */
	public function test_manager_can_kick_user_out(): void {
		Event::fake();

		$this->actingAs($this->admin)->delete(action('Match\MatchUserController@removePlayer', $this->match), [
			'user' => $this->player->id,
			'message' => 'I hate you',
		])->assertSessionHas('alert', __('match/removePlayer.removed'));

		$this->assertFalse($this->player->inMatch($this->match));

		Event::assertDispatched(PlayerRemoved::class, function ($event) {
			return $event->user->id === $this->player->id;
		});
	}

	/**
	 * @test
	 * @group match
	 * @group removePlayer
	 */
	public function test_handles_player_removed(): void {
		Notification::fake();

		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->admin->id}_{$this->match->id}_manager"), Mockery::any())->andReturn(true);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("match_{$this->match->id}"), Mockery::any())->andReturn($this->match);

		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->username}_nextMatch"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_player"));
		Cache::shouldReceive('tags')->once()->with("{$this->player->username}_played")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->match->id}_registeredPlayers"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->match->id}_isFull"));

		$this->actingAs($this->admin)->delete(action('Match\MatchUserController@removePlayer', $this->match), [
			'user' => $this->player->id,
			'message' => 'I hate you',
		])->assertSessionHas('alert', __('match/removePlayer.removed'));

		$this->assertFalse($this->match->hasPlayer($this->player));

		Notification::assertSentTo($this->player, PlayerRemovedNotification::class);

	}

	/**
	 * @test
	 * @group match
	 * @group removePlayer
	 */
	public function test_cant_kick_user_out_finished_match(): void {
		Event::fake();
		$this->match->date_time = Carbon::now()->subDay();
		$this->match->save();

		$this->actingAs($this->admin)->delete(action('Match\MatchUserController@removePlayer', $this->match), [
			'user' => $this->player->id,
			'message' => 'I hate you',
		])->assertStatus(302)->assertSessionHasErrors('ended');

		$this->assertTrue($this->player->inMatch($this->match));

		Event::assertNotDispatched(PlayerRemoved::class, function ($event) {
			return $event->user->id === $this->player->id;
		});
	}

	/**
	 * @test
	 * @group match
	 * @group removePlayer
	 */
	public function test_player_cant_kick_user_out(): void {
		Event::fake();

		$this->actingAs($this->player)->delete(action('Match\MatchUserController@removePlayer', $this->match), [
			'user' => $this->player->id,
			'message' => 'I hate you',
		])->assertStatus(403);

		$this->assertTrue($this->player->inMatch($this->match));

		Event::assertNotDispatched(PlayerRemoved::class, function ($event) {
			return $event->user->id === $this->player->id;
		});
	}

	/**
	 * @test
	 * @group match
	 * @group removePlayer
	 */
	public function test_guest_cant_kick_user_out(): void {
		Event::fake();

		$this->delete(action('Match\MatchUserController@removePlayer', $this->match), [
			'user' => $this->player->id,
			'message' => 'I hate you',
		])->assertRedirect(action('Auth\LoginController@showLoginForm'));

		$this->assertTrue($this->player->inMatch($this->match));

		Event::assertNotDispatched(PlayerRemoved::class, function ($event) {
			return $event->user->id === $this->player->id;
		});
	}

	/**
	 * @test
	 * @group match
	 * @group removePlayer
	 */
	public function test_sends_notification_on_user_removed_event_empty_message(): void {
		Notification::fake();

		$listener = new SendPlayerRemovedNotification();
		$listener->handle(new PlayerRemoved($this->match, $this->player));

		Notification::assertSentTo($this->player, PlayerRemovedNotification::class);

	}

}
