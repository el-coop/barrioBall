<?php

namespace Tests\Feature\Match;

use App\Events\Match\PlayerRemoved;
use App\Listeners\Match\SendPlayerRemovedNotification;
use App\Models\Admin;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\PlayerRemoved as PlayerRemovedNotification;
use Event;
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
	 * @group Match
	 * @group removePlayer
	 */
	public function test_manager_can_kick_user_out() {
		Event::fake();

		$this->actingAs($this->admin)->delete(action('Match\MatchUsersController@removePlayer', $this->match), [
			'user' => $this->player->id,
			'message' => 'I hate you',
		])->assertSessionHas('alert', __('match/removePlayer.removed'));

		$this->assertFalse($this->player->inMatch($this->match));

		Event::assertDispatched(PlayerRemoved::class, function ($event) {
			return $event->user->id === $this->player->id;
		});
	}

	public function test_player_cant_kick_user_out() {
		Event::fake();

		$this->actingAs($this->player)->delete(action('Match\MatchUsersController@removePlayer', $this->match), [
			'user' => $this->player->id,
			'message' => 'I hate you',
		])->assertStatus(403);

		$this->assertTrue($this->player->inMatch($this->match));

		Event::assertNotDispatched(PlayerRemoved::class, function ($event) {
			return $event->user->id === $this->player->id;
		});
	}

	public function test_guest_cant_kick_user_out() {
		Event::fake();

		$this->delete(action('Match\MatchUsersController@removePlayer', $this->match), [
			'user' => $this->player->id,
			'message' => 'I hate you',
		])->assertRedirect(action('Auth\LoginController@showLoginForm'));

		$this->assertTrue($this->player->inMatch($this->match));

		Event::assertNotDispatched(PlayerRemoved::class, function ($event) {
			return $event->user->id === $this->player->id;
		});
	}

	public function test_sends_notification_on_user_removed_event() {
		Notification::fake();

		$listener = new SendPlayerRemovedNotification();
		$listener->handle(new PlayerRemoved($this->player, Match::first(), ''));

		Notification::assertSentTo($this->player, PlayerRemovedNotification::class);

	}
}
