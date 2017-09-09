<?php

namespace Tests\Feature\Match;

use App\Events\Match\JoinRequest;
use App\Events\Match\UserJoined;
use App\Events\Match\UserRejected;
use App\Listeners\Match\SendJoinRequestNotification;
use App\Listeners\Match\SendJoinRequestRejectedNotification;
use App\Listeners\Match\SendUserJoinedNotification;
use App\Notifications\Match\JoinRequestRejected;
use App\Models\Admin;
use App\Models\Match;
use App\Models\Player;
use App\Models\User;
use App\Notifications\Match\JoinMatchRequest;
use App\Notifications\Match\MatchJoined;
use Illuminate\Support\Facades\Event;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JoinTest extends TestCase
{
	use DatabaseMigrations;

	protected $match;
	protected $player;

	protected function setMatch(){
		factory(Admin::class)->create()->each(function($user){
			$user->user()->save(factory(User::class)->make());
		});

		factory(Match::class)->create()->each(function($match){
			$match->addManager(User::first());
		});

		return Match::first();
	}

	public function setUp() {
		parent::setUp();
		$this->match = $this->setMatch();
		$this->player = factory(Player::class)->create();
		$this->player->user()->save(factory(User::class)->make());
	}

	public function test_admin_joins_automatically(){
		Event::fake();

		$response = $this->actingAs(Admin::first()->user)->post(action('Match\MatchUsersController@joinMatch', Match::first()),[]);

		$response->assertStatus(302);
		$response->assertSessionHas('alert',__('match/show.joined'));

		$this->assertDatabaseHas('match_user',[
			'user_id' => Admin::first()->user->id,
			'match_id' => Match::first()->id,
			'role' => 'player',
		]);

		Event::assertDispatched(UserJoined::class, function ($event) {
			return $event->user->id === Admin::first()->id;
		});
	}

	public function test_sends_email_to_admins_when_user_joins(){
		Notification::fake();

		$listener = new SendUserJoinedNotification();
		$listener->handle(new UserJoined($this->player->user,Match::first()));

		Notification::assertSentTo(Admin::first()->user, MatchJoined::class);
	}

	public function test_player_can_send_join_request(){
		Event::fake();
		$response = $this->actingAs($this->player->user)->post(action('Match\MatchUsersController@joinMatch', Match::first()),[
			'message' => 'bla'
		]);

		$response->assertStatus(302);
		$response->assertSessionHas('alert',__('match/show.joinMatchSent'));

		$this->assertDatabaseHas('join_match_requests',[
			'user_id' => $this->player->user->id,
			'match_id' => Match::first()->id
		]);

		Event::assertDispatched(JoinRequest::class, function ($event) {
			return $event->user->id === $this->player->user->id && $event->message = 'bla';
		});
	}

	public function test_player_cant_repeat_send_join_request(){
		Match::first()->joinRequests()->save($this->player->user);
		$this->doesntExpectEvents(JoinRequest::class);
		$response = $this->actingAs($this->player->user)->post(action('Match\MatchUsersController@joinMatch', Match::first()),[
			'message' => 'bla'
		]);

		$response->assertStatus(403);

	}

	public function test_sends_email_when_request_sent(){
		Notification::fake();

		$listener = new SendJoinRequestNotification();
		$listener->handle(new JoinRequest($this->player->user,Match::first(),'Test Mail'));

		Notification::assertSentTo(Admin::first()->user, JoinMatchRequest::class);
	}

	public function test_can_reject_user_request(){
		Match::first()->joinRequests()->save($this->player->user);
		Event::fake();
		$response = $this->actingAs($this->match->managers()->first())->delete(action('Match\MatchUsersController@rejectJoin', Match::first()),[
			'user' => $this->player->user->id,
			'message' => 'bla'
		]);

		$response->assertStatus(302);
		$response->assertSessionHas('alert');

		$this->assertDatabaseMissing('join_match_requests',[
			'user_id' => $this->player->user->id,
			'match_id' => Match::first()->id
		]);

		Event::assertDispatched(UserRejected::class, function ($event) {
			return $event->user->id === $this->player->user->id && $event->message = 'bla';
		});
	}


	public function test_cant_reject_non_existent_user_request(){
		Event::fake();
		$response = $this->actingAs($this->match->managers()->first())->delete(action('Match\MatchUsersController@rejectJoin', Match::first()),[
			'user' => $this->player->user->id,
			'message' => 'bla'
		]);

		$response->assertStatus(302);
		$response->assertSessionHas('error');

		Event::assertNotDispatched(UserRejected::class, function ($event) {
			return $event->user->id === $this->player->user->id && $event->message = 'bla';
		});
	}

	public function test_notfies_user_when_rejected(){
		Notification::fake();

		$listener = new SendJoinRequestRejectedNotification();
		$listener->handle(new UserRejected($this->player->user,Match::first(),'Test Mail'));

		Notification::assertSentTo($this->player->user, JoinRequestRejected::class);
	}
}
