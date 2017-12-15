<?php

namespace Tests\Feature\Match;

use App\Events\Match\JoinRequestCanceled;
use App\Events\Match\JoinRequestSent;
use App\Models\Match;
use App\Models\User;
use Cache;
use Event;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CancelJoinRequestTest extends TestCase {
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
	 * @group cancelJoinRequest
	 * @group match
	 */
	public function test_cant_cancel_join_request_if_not_joind(): void {
		$this->actingAs($this->player)->post(action('Match\MatchUserController@cancelJoinRequest', $this->match), [])
			->assertStatus(403);
	}

	/**
	 * @test
	 * @group cancelJoinRequest
	 * @group match
	 */
	public function test_can_cancel_after_joining(): void {
		Event::fake();
		$this->match->addJoinRequest($this->player);
		$this->actingAs($this->player)->post(action('Match\MatchUserController@cancelJoinRequest', $this->match), [])
			->assertStatus(302)
			->assertSessionHas('alert', __('match/show.cancelMessage'));

		$this->assertDatabaseMissing('join_match_requests', [
			'user_id' => $this->player->id,
			'match_id' => $this->match->id]);

		Event::assertDispatched(JoinRequestCanceled::class, function ($event) {
			return $event->user->id === $this->player->id && $event->match->id = $this->match->id;
		});
	}

	/**
	 * @test
	 * @group cancelJoinRequest
	 * @group match
	 */
	public function test_handles_cancel_join_requests_event(): void {
		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->player->id}_{$this->match->id}_joinRequest"), Mockery::any())->andReturn(true);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("match_{$this->match->id}"), Mockery::any())->andReturn($this->match);
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->manager->username}_requests"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->match->id}_joinRequests"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_joinRequest"));

		$this->match->addJoinRequest($this->player);
		$this->actingAs($this->player)->post(action('Match\MatchUserController@cancelJoinRequest', $this->match), [])
			->assertStatus(302)
			->assertSessionHas('alert', __('match/show.cancelMessage'));

		$this->assertDatabaseMissing('join_match_requests', [
			'user_id' => $this->player->id,
			'match_id' => $this->match->id]);

	}


	/**
	 * @test
	 * @group cancelJoinRequest
	 * @group match
	 */
	public function test_can_rejoin_after_canceling(): void {
		Event::fake();

		$this->actingAs($this->player)->post(action('Match\MatchUserController@joinMatch', $this->match), [
			'message' => 'bla',
		])->assertStatus(302)
			->assertSessionHas('alert', __('match/show.joinMatchSent'));

		$this->assertTrue($this->match->hasJoinRequest($this->player));

		Event::assertDispatched(JoinRequestSent::class, function ($event) {
			return $event->user->id === $this->player->id && $event->message = 'bla';
		});
	}
}
