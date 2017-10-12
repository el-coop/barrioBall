<?php

namespace Tests\Feature\Match;

use App\Models\Match;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase {
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
	 * @group showMatch
	 */
	public function test_shows_match_page(): void {
		$this->match = Match::find($this->match->id);
		$response = $this->get($this->match->url);
		$response->assertStatus(200);
		$response->assertSee("<title>" . htmlentities($this->match->name, ENT_QUOTES));
		$response->assertSee("{$this->match->date} {$this->match->time}");
		$response->assertSee("{$this->match->registeredPlayers()->count()}/{$this->match->players}");
		$response->assertDontSeeText(__('match/show.waitingForResponse'));
	}

	/**
	 * @test
	 * @group showMatch
	 */
	public function test_logged_sees_join_match_button(): void {
		$response = $this->actingAs($this->manager)->get($this->match->url);
		$response->assertSeeText(__('match/show.joinRequest'));
		$response->assertDontSeeText(__('match/show.login'));
		$response->assertDontSeeText(__('match/show.matchFull'));
		$response->assertDontSeeText(__('match/show.leaveMatch'));
		$response->assertDontSeeText(__('match/show.waitingForResponse'));
	}

	/**
	 * @test
	 * @group showMatch
	 */
	public function test_sent_request_sees_request_sent_button(): void {
		$this->match->addJoinRequest($this->player);
		$response = $this->actingAs($this->player)->get($this->match->url);
		$response->assertSeeText(__('match/show.waitingForResponse'));
		$response->assertDontSeeText(__('match/show.joinRequest'));
		$response->assertDontSeeText(__('match/show.login'));
		$response->assertDontSeeText(__('match/show.matchFull'));
		$response->assertDontSeeText(__('match/show.leaveMatch'));
	}


	/**
	 * @test
	 * @group showMatch
	 */
	public function test_not_logged_sees_login_button(): void {
		$response = $this->get($this->match->url);
		$response->assertSeeText(__('match/show.login'));
		$response->assertDontSeeText(__('match/show.joinRequest'));
		$response->assertDontSeeText(__('match/show.matchFull'));
		$response->assertDontSeeText(__('match/show.leaveMatch'));
		$response->assertDontSeeText(__('match/show.waitingForResponse'));

	}

	/**
	 * @test
	 * @group showMatch
	 */
	public function test_joined_sees_leave_button(): void {
		$this->match->addPlayer($this->manager);
		$response = $this->actingAs($this->manager)->get($this->match->url);
		$response->assertSeeText(__('match/show.leaveMatch'));
		$response->assertDontSeeText(__('match/show.joinRequest'));
		$response->assertDontSeeText(__('match/show.matchFull'));
		$response->assertDontSeeText(__('match/show.login'));
		$response->assertDontSeeText(__('match/show.waitingForResponse'));
	}

	/**
	 * @test
	 * @group showMatch
	 */
	public function test_full_match_shows_full_button(): void {
		factory(User::class, $this->match->players)->create()->each(function ($player) {
			$this->match->addPlayer($player);
		});
		$response = $this->actingAs($this->manager)->get($this->match->url);
		$response->assertSeeText(__('match/show.matchFull'));
		$response->assertDontSeeText(__('match/show.joinRequest'));
		$response->assertDontSeeText(__('match/show.leaveMatch'));
		$response->assertDontSeeText(__('match/show.login'));
		$response->assertDontSeeText(__('match/show.waitingForResponse'));
	}


	/**
	 * @test
	 * @group showMatch
	 */
	public function test_admin_sees_delete_match_button(): void {
		$response = $this->actingAs($this->manager)->get($this->match->url);
		$response->assertSeeText(__('match/show.deleteMatch'));
	}


	/**
	 * @test
	 * @group showMatch
	 */
	public function test_non_manager_cant_see_delete_match_button(): void {
		$response = $this->actingAs($this->player)->get($this->match->url);
		$response->assertDontSeeText(__('match/show.deleteMatch'));
	}

	/**
	 * @test
	 * @group showMatch
	 */
	public function test_invite_managers(): void {
		$response = $this->actingAs($this->manager)->get($this->match->url);
		$response->assertSeeText(__('match/show.inviteManagers'));

	}

	/**
	 * @test
	 * @group showMatch
	 */
	public function test_non_manager_cant_invite_others(): void {
		$response = $this->actingAs($this->player)->get($this->match->url);
		$response->assertDontSeeText(__('match/show.inviteManagers'));
	}


	/**
	 * @test
	 * @group showMatch
	 */
	public function test_manager_sees_leave_managment_button(): void {
		$this->match->addManager($this->player);
		$response = $this->actingAs($this->player)->get($this->match->url);
		$response->assertSeeText(__('match/show.stopManaging'));

	}


	/**
	 * @test
	 * @group showMatch
	 */
	public function test_last_manager_cant_see_leave_managment_button(): void {
		$response = $this->actingAs($this->manager)->get($this->match->url);
		$response->assertDontSeeText(__('match/show.stopManaging'));

	}

	/**
	 * @test
	 * @group showMatch
	 */
	public function test_non_manager_cant_see_leave_managment_button(): void {
		$response = $this->actingAs($this->player)->get($this->match->url);
		$response->assertDontSeeText(__('match/show.stopManaging'));
	}
}
