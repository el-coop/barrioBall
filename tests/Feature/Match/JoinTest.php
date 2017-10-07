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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
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
	 */
	public function test_admin_joins_automatically(): void {
		Event::fake();

		$response = $this->actingAs($this->manager)->post(action('Match\MatchUsersController@joinMatch', $this->match), []);

		$response->assertStatus(302);
		$response->assertSessionHas('alert', __('match/show.joined'));

		$this->assertTrue($this->match->hasPlayer($this->manager));

		Event::assertDispatched(UserJoined::class, function ($event) {
			return $event->user->id === $this->manager->id;
		});
	}

	/**
	 * @test
	 */
	public function test_admin_cant_repeat_join(): void {
		$this->match->addPlayer($this->manager);
		Event::fake();

		$response = $this->actingAs($this->manager)->post(action('Match\MatchUsersController@joinMatch', $this->match), []);

		$response->assertStatus(403);
		Event::assertNotDispatched(UserJoined::class);
	}

	/**
	 * @test
	 */
	public function test_admin_cant_join_full_match(): void {
		factory(User::class, $this->match->players)->create()->each(function ($player) {
			$this->match->addPlayer($player);
		});

		Event::fake();

		$response = $this->actingAs($this->manager)->post(action('Match\MatchUsersController@joinMatch', $this->match), []);

		$response->assertStatus(403);
		Event::assertNotDispatched(UserJoined::class);
	}

	/**
	 * @test
	 */
	public function test_sends_email_to_admins_when_user_joins(): void {
		Notification::fake();

		$listener = new SendUserJoinedNotification();
		$listener->handle(new UserJoined($this->player, $this->match));

		Notification::assertSentTo($this->manager, MatchJoined::class);
	}

	/**
	 * @test
	 */
	public function test_player_can_send_join_request(): void {
		Event::fake();
		$response = $this->actingAs($this->player)->post(action('Match\MatchUsersController@joinMatch', $this->match), [
			'message' => 'bla',
		]);

		$response->assertStatus(302);
		$response->assertSessionHas('alert', __('match/show.joinMatchSent'));

		$this->assertTrue($this->match->hasJoinRequest($this->player));

		Event::assertDispatched(JoinRequestSent::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}

	/**
	 * @test
	 */
	public function test_player_cant_repeat_send_join_request(): void {
		$this->match->addJoinRequest($this->player);
		Event::fake();

		$response = $this->actingAs($this->player)->post(action('Match\MatchUsersController@joinMatch', $this->match), [
			'message' => 'bla',
		]);

		$response->assertStatus(403);
		Event::assertNotDispatched(JoinRequestSent::class);
	}


	/**
	 * @test
	 */
	public function test_player_cant_send_join_request_to_full_match(): void {
		factory(User::class, $this->match->players)->create()->each(function ($player) {
			$this->match->addPlayer($player);
		});

		Event::fake();

		$response = $this->actingAs($this->manager)->post(action('Match\MatchUsersController@joinMatch', $this->match), []);

		$response->assertStatus(403);
		Event::assertNotDispatched(JoinRequestSent::class);
	}

	/**
	 * @test
	 */
	public function test_not_loged_in_cant_send_join_request(): void {
		Event::fake();
		$response = $this->post(action('Match\MatchUsersController@joinMatch', $this->match), [
			'message' => 'bla',
		]);

		$this->assertFalse($this->match->hasJoinRequest($this->player));

		$response->assertStatus(302);
		$response->assertRedirect(action('Auth\LoginController@showLoginForm'));
		Event::assertNotDispatched(JoinRequestSent::class);

	}

	/**
	 * @test
	 */
	public function test_sends_email_when_request_sent(): void {
		Notification::fake();

		$listener = new SendJoinRequestNotification();
		$listener->handle(new JoinRequestSent($this->manager, $this->match, 'Test Mail'));

		Notification::assertSentTo($this->manager, JoinMatchRequest::class);
	}

	/**
	 * @test
	 */
	public function test_can_reject_user_request(): void {
		$this->match->addJoinRequest($this->player);
		Event::fake();
		$response = $this->actingAs($this->manager)->delete(action('Match\MatchUsersController@rejectJoin', $this->match), [
			'user' => $this->player->id,
			'message' => 'bla',
		]);

		$response->assertStatus(302);
		$response->assertSessionHas('alert');

		$this->assertFalse($this->match->hasJoinRequest($this->player));

		Event::assertDispatched(UserRejected::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}


	/**
	 * @test
	 */
	public function test_cant_reject_non_existent_user_request(): void {
		Event::fake();
		$response = $this->actingAs($this->manager)->delete(action('Match\MatchUsersController@rejectJoin', $this->match), [
			'user' => $this->player->id,
			'message' => 'bla',
		]);

		$response->assertStatus(302);
		$response->assertSessionHas('error');

		Event::assertNotDispatched(UserRejected::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}

	/**
	 * @test
	 */
	public function test_cant_reject_when_not_logged_in(): void {
		$this->match->addJoinRequest($this->player);
		Event::fake();
		$response = $this->delete(action('Match\MatchUsersController@rejectJoin', $this->match), [
			'user' => $this->player->id,
			'message' => 'bla',
		]);

		$response->assertStatus(302);
		$response->assertRedirect(action('Auth\LoginController@showLoginForm'));
		$this->assertTrue($this->match->hasJoinRequest($this->player));

		Event::assertNotDispatched(UserRejected::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}


	public function test_notfies_user_when_rejected() {
		Notification::fake();

		$listener = new SendJoinRequestRejectedNotification();
		$listener->handle(new UserRejected($this->player, $this->match, 'Test Mail'));

		Notification::assertSentTo($this->player, JoinRequestRejected::class);
	}

	/**
	 * @test
	 */
	public function test_can_accept_user_join_request(): void {
		$this->match->addJoinRequest($this->player);
		Event::fake();
		$response = $this->actingAs($this->manager)->post(action('Match\MatchUsersController@acceptJoin', $this->match), [
			'user' => $this->player->id,
			'message' => 'bla',
		]);

		$response->assertStatus(302);
		$response->assertSessionHas('alert');

		$this->assertfalse($this->match->hasJoinRequest($this->player));
		$this->assertTrue($this->match->hasPlayer($this->player));


		Event::assertDispatched(UserJoined::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});

	}

	/**
	 * @test
	 */
	public function test_cant_accept_non_existent_request(): void {
		Event::fake();
		$response = $this->actingAs($this->manager)->post(action('Match\MatchUsersController@acceptJoin', $this->match), [
			'user' => $this->player->id,
			'message' => 'bla',
		]);

		$response->assertStatus(302);
		$response->assertSessionHas('error');

		$this->assertFalse($this->match->hasPlayer($this->player));

		Event::assertNotDispatched(UserJoined::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}

	/**
	 * @test
	 */
	public function test_cant_accept_when_not_logged_in(): void {
		$this->match->addJoinRequest($this->player);
		Event::fake();
		$response = $this->post(action('Match\MatchUsersController@acceptJoin', $this->match), [
			'user' => $this->player->id,
			'message' => 'bla',
		]);

		$response->assertStatus(302);
		$response->assertRedirect(action('Auth\LoginController@showLoginForm'));


		$this->assertTrue($this->match->hasJoinRequest($this->player));
		$this->assertFalse($this->match->hasPlayer($this->player));


		Event::assertNotDispatched(UserJoined::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}


	/**
	 * @test
	 */
	public function test_user_is_notified_when_request_is_accepted(): void {
		Notification::fake();

		$listener = new SendJoinRequestAcceptedNotification();
		$listener->handle(new UserJoined($this->player, $this->match, 'Test'));

		Notification::assertSentTo($this->player, JoinRequestAccepted::class);
	}
}
