<?php

namespace Tests\Feature\Match;

use App\Events\Match\JoinRequestSent;
use App\Events\Match\UserJoined;
use App\Events\Match\UserRejected;
use App\Listeners\Match\SendJoinRequestAcceptedNotification;
use App\Listeners\Match\SendJoinRequestNotification;
use App\Listeners\Match\SendJoinRequestRejectedNotification;
use App\Listeners\Match\SendUserJoinedNotification;
use App\Notifications\Match\JoinRequestAccepted;
use App\Notifications\Match\JoinRequestRejected;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\JoinMatchRequest;
use App\Notifications\Match\MatchJoined;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Event;
use Notification;
use Tests\TestCase;

class JoinTest extends TestCase {
	use RefreshDatabase;

	protected $match;
	protected $player;
	protected $manager;

	public function setUp() {
		parent::setUp();
		$this->manager = factory(User::class)->create();
		$this->match = factory(Match::class)->create();
		$this->player = factory(User::class)->create();
		$this->match->addManager($this->manager);
	}


	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_admin_joins_automatically(): void {
		Event::fake();

		$this->actingAs($this->manager)->post(action('Match\MatchUsersController@joinMatch', $this->match), [])
			->assertStatus(302)
			->assertSessionHas('alert', __('match/show.joined'));

		$this->assertTrue($this->match->hasPlayer($this->manager));

		Event::assertDispatched(UserJoined::class, function ($event) {
			return $event->user->id === $this->manager->id;
		});
	}

	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_admin_cant_repeat_join(): void {
		Event::fake();

		$this->match->addPlayer($this->manager);
		$this->actingAs($this->manager)->post(action('Match\MatchUsersController@joinMatch', $this->match), [])
			->assertStatus(403);

		Event::assertNotDispatched(UserJoined::class);
	}

	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_admin_cant_join_full_match(): void {
		Event::fake();

		factory(User::class, $this->match->players)->create()->each(function ($player) {
			$this->match->addPlayer($player);
		});

		$this->actingAs($this->manager)->post(action('Match\MatchUsersController@joinMatch', $this->match), [])
			->assertStatus(403);

		Event::assertNotDispatched(UserJoined::class);
	}

	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_sends_email_to_admins_when_user_joins(): void {
		Notification::fake();

		$listener = new SendUserJoinedNotification();
		$listener->handle(new UserJoined($this->match, $this->player));

		Notification::assertSentTo($this->manager, MatchJoined::class);
	}

	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_player_can_send_join_request(): void {
		Event::fake();

		$this->actingAs($this->player)->post(action('Match\MatchUsersController@joinMatch', $this->match), [
			'message' => 'bla',
		])->assertStatus(302)
			->assertSessionHas('alert', __('match/show.joinMatchSent'));

		$this->assertTrue($this->match->hasJoinRequest($this->player));

		Event::assertDispatched(JoinRequestSent::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}

	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_player_cant_repeat_send_join_request(): void {
		Event::fake();

		$this->match->addJoinRequest($this->player);

		$this->actingAs($this->player)->post(action('Match\MatchUsersController@joinMatch', $this->match), [
			'message' => 'bla',
		])->assertStatus(403);

		Event::assertNotDispatched(JoinRequestSent::class);
	}

	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_player_cant_send_join_request_to_full_match(): void {
		Event::fake();

		factory(User::class, $this->match->players)->create()->each(function ($player) {
			$this->match->addPlayer($player);
		});

		$this->actingAs($this->manager)->post(action('Match\MatchUsersController@joinMatch', $this->match), [])
			->assertStatus(403);
		Event::assertNotDispatched(JoinRequestSent::class);
	}

	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_not_logged_in_cant_send_join_request(): void {
		Event::fake();
		$this->post(action('Match\MatchUsersController@joinMatch', $this->match), [
			'message' => 'bla',
		])->assertRedirect(action('Auth\LoginController@showLoginForm'));

		$this->assertFalse($this->match->hasJoinRequest($this->player));


		Event::assertNotDispatched(JoinRequestSent::class);
	}


	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_sends_email_when_request_sent(): void {
		Notification::fake();

		$listener = new SendJoinRequestNotification();
		$listener->handle(new JoinRequestSent($this->match, $this->manager, 'Test Mail'));

		Notification::assertSentTo($this->manager, JoinMatchRequest::class);
	}


	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_can_reject_user_request(): void {
		Event::fake();
		$this->match->addJoinRequest($this->player);
		$this->actingAs($this->manager)->delete(action('Match\MatchUsersController@rejectJoin', $this->match), [
			'user' => $this->player->id,
			'message' => 'bla',
		])->assertStatus(302)
			->assertSessionHas('alert', __('match/requests.rejected'));

		$this->assertFalse($this->match->hasJoinRequest($this->player));

		Event::assertDispatched(UserRejected::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}


	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_cant_reject_non_existent_user_request(): void {
		Event::fake();
		$this->actingAs($this->manager)->delete(action('Match\MatchUsersController@rejectJoin', $this->match), [
			'user' => $this->player->id,
			'message' => 'bla',
		])->assertStatus(302)
			->assertSessionHasErrors('request', __('match/requests.requestNotExistent'));

		Event::assertNotDispatched(UserRejected::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}

	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_cant_reject_when_not_logged_in(): void {
		Event::fake();
		$this->match->addJoinRequest($this->player);
		$this->delete(action('Match\MatchUsersController@rejectJoin', $this->match), [
			'user' => $this->player->id,
			'message' => 'bla',
		])->assertStatus(302)
			->assertRedirect(action('Auth\LoginController@showLoginForm'));

		$this->assertTrue($this->match->hasJoinRequest($this->player));

		Event::assertNotDispatched(UserRejected::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}

	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_notfies_user_when_rejected(): void {
		Notification::fake();

		$listener = new SendJoinRequestRejectedNotification();
		$listener->handle(new UserRejected($this->match, $this->player, 'Test Mail'));

		Notification::assertSentTo($this->player, JoinRequestRejected::class);
	}

	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_can_accept_user_join_request(): void {
		Event::fake();
		$this->match->addJoinRequest($this->player);
		$this->actingAs($this->manager)->post(action('Match\MatchUsersController@acceptJoin', $this->match), [
			'user' => $this->player->id,
			'message' => 'bla',
		])->assertStatus(302)
			->assertSessionHas('alert', __('match/requests.accepted'));

		$this->assertfalse($this->match->hasJoinRequest($this->player));
		$this->assertTrue($this->match->hasPlayer($this->player));


		Event::assertDispatched(UserJoined::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});

	}

	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_cant_accept_non_existent_request(): void {
		Event::fake();
		$this->actingAs($this->manager)->post(action('Match\MatchUsersController@acceptJoin', $this->match), [
			'user' => $this->player->id,
			'message' => 'bla',
		])->assertStatus(302)
			->assertSessionHasErrors('request', __('match/requests.requestNotExistent'));

		$this->assertFalse($this->match->hasPlayer($this->player));

		Event::assertNotDispatched(UserJoined::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}

	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_cant_accept_when_not_logged_in(): void {
		Event::fake();
		$this->match->addJoinRequest($this->player);
		$this->post(action('Match\MatchUsersController@acceptJoin', $this->match), [
			'user' => $this->player->id,
			'message' => 'bla',
		])->assertRedirect(action('Auth\LoginController@showLoginForm'));


		$this->assertTrue($this->match->hasJoinRequest($this->player));
		$this->assertFalse($this->match->hasPlayer($this->player));


		Event::assertNotDispatched(UserJoined::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}


	/**
	 * @test
	 * @group match
	 * @group joinMatch
	 */
	public function test_user_is_notified_when_request_is_accepted(): void {
		Notification::fake();
		Auth::login($this->manager);
		$listener = new SendJoinRequestAcceptedNotification();
		$listener->handle(new UserJoined($this->match, $this->player, 'Test'));

		Notification::assertSentTo($this->player, JoinRequestAccepted::class);
	}
}
