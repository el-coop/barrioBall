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
	 * @group match
	 * @group showMatch
	 */
	public function test_shows_match_page(): void {
		$this->match = Match::find($this->match->id);
		$this->get($this->match->url)
			->assertStatus(200)
			->assertSee("<title>" . htmlentities($this->match->name, ENT_QUOTES))
			->assertSee("{$this->match->date} {$this->match->time}")
			->assertSee("{$this->match->registeredPlayers()->count()}/{$this->match->players}");
	}

	/**
	 * @test
	 * @group match
	 * @group showMatch
	 * @group joinMatch
	 */
	public function test_logged_sees_join_match_button(): void {
		$this->actingAs($this->manager)->get($this->match->url)
			->assertSeeText(__('match/show.joinRequest'))
			->assertDontSeeText(__('match/show.login'))
			->assertDontSeeText(__('match/show.matchFull'))
			->assertDontSeeText(__('match/show.leaveMatch'))
			->assertDontSeeText(__('match/show.waitingForResponse'));
	}

	/**
	 * @test
	 * @group match
	 * @group showMatch
	 * @group joinMatch
	 */
	public function test_sent_request_sees_request_sent_button(): void {
		$this->match->addJoinRequest($this->player);
		$this->actingAs($this->player)->get($this->match->url)
			->assertSeeText(__('match/show.waitingForResponse'))
			->assertDontSeeText(__('match/show.joinRequest'))
			->assertDontSeeText(__('match/show.login'))
			->assertDontSeeText(__('match/show.matchFull'))
			->assertDontSeeText(__('match/show.leaveMatch'));
	}


	/**
	 * @test
	 * @group showMatch
	 */
	public function test_not_logged_sees_login_button(): void {
		$this->get($this->match->url)
			->assertSeeText(__('match/show.login'))
			->assertDontSeeText(__('match/show.joinRequest'))
			->assertDontSeeText(__('match/show.matchFull'))
			->assertDontSeeText(__('match/show.leaveMatch'))
			->assertDontSeeText(__('match/show.waitingForResponse'));

	}

	/**
	 * @test
	 * @group showMatch
	 * @group leaveMatch
	 */
	public function test_joined_sees_leave_button(): void {
		$this->match->addPlayer($this->manager);
		$this->actingAs($this->manager)->get($this->match->url)
			->assertSeeText(__('match/show.leaveMatch'))
			->assertDontSeeText(__('match/show.joinRequest'))
			->assertDontSeeText(__('match/show.matchFull'))
			->assertDontSeeText(__('match/show.login'))
			->assertDontSeeText(__('match/show.waitingForResponse'));
	}

	/**
	 * @test
	 * @group showMatch
	 * @group joinMatch
	 */
	public function test_full_match_shows_full_button(): void {
		factory(User::class, $this->match->players)->create()->each(function ($player) {
			$this->match->addPlayer($player);
		});
		$this->actingAs($this->manager)->get($this->match->url)
			->assertSeeText(__('match/show.matchFull'))
			->assertDontSeeText(__('match/show.joinRequest'))
			->assertDontSeeText(__('match/show.leaveMatch'))
			->assertDontSeeText(__('match/show.login'))
			->assertDontSeeText(__('match/show.waitingForResponse'));
	}


	/**
	 * @test
	 * @group showMatch
	 * @group deleteMatch
	 */
	public function test_admin_sees_delete_match_button(): void {
		$this->actingAs($this->manager)->get($this->match->url)
			->assertSeeText(__('match/show.deleteMatch'));
	}


	/**
	 * @test
	 * @group showMatch
	 * @group deleteMatch
	 */
	public function test_non_manager_cant_see_delete_match_button(): void {
		$this->actingAs($this->player)->get($this->match->url)
			->assertDontSeeText(__('match/show.deleteMatch'));
	}

	/**
	 * @test
	 * @group showMatch
	 * @group inviteManagers
	 */
	public function test_invite_managers(): void {
		$this->actingAs($this->manager)->get($this->match->url)
			->assertSeeText(__('match/show.inviteManagers'));
	}

	/**
	 * @test
	 * @group showMatch
	 * @group inviteManagers
	 */
	public function test_non_manager_cant_invite_others(): void {
		$this->actingAs($this->player)->get($this->match->url)
			->assertDontSeeText(__('match/show.inviteManagers'));
	}


	/**
	 * @test
	 * @group showMatch
	 * @group leaveManagement
	 */
	public function test_manager_sees_leave_managment_button(): void {
		$this->match->addManager($this->player);
		$this->actingAs($this->player)->get($this->match->url)
			->assertSeeText(__('match/show.stopManaging'));
	}


	/**
	 * @test
	 * @group showMatch
	 * @group leaveManagement
	 */
	public function test_last_manager_cant_see_leave_managment_button(): void {
		$this->actingAs($this->manager)->get($this->match->url)
			->assertDontSeeText(__('match/show.stopManaging'));
	}

	/**
	 * @test
	 * @group showMatch
	 * @group leaveManagement
	 */
	public function test_non_manager_cant_see_leave_managment_button(): void {
		$this->actingAs($this->player)->get($this->match->url)
			->assertDontSeeText(__('match/show.stopManaging'));
	}

	/**
	 * @test
	 * @group Match
	 * @group removePlayer
	 * @group showMatch
	 */
	public function test_shows_remove_button_to_manager(): void {
		$this->match->addPlayer($this->player);
		$this->actingAs($this->manager)
			->get(action('Match\MatchController@showMatch', $this->match))
			->assertSee("url: '" . action('Match\MatchUsersController@removePlayer', $this->match) . "'")
			->assertSee('<button class="btn btn-danger"');
	}

	/**
	 * @test
	 * @group Match
	 * @group removePlayer
	 * @group showMatch
	 */
	public function test_doesnt_show_remove_button_to_player() {
		$this->match->addPlayer($this->manager);
		$this->actingAs($this->player)
			->get(action('Match\MatchController@showMatch', $this->match))
			->assertDontSee('<button class="btn btn-danger"');
	}

	/**
	 * @test
	 * @group Match
	 * @group removePlayer
	 * @group showMatch
	 */

	public function test_doesnt_show_remove_button_to_guest() {
		$this->match->addPlayer($this->player);
		$this->get(action('Match\MatchController@showMatch', $this->match))
			->assertDontSee('<button class="btn btn-danger"');
	}

	/**
	 * @test
	 * @group Match
	 * @group removePlayer
	 * @group showMatch
	 */
	public function test_doesnt_show_remove_button_on_manager() {
		$this->match->addPlayer($this->player);
		$this->match->addManager($this->player);
		$this->actingAs($this->manager)
			->get(action('Match\MatchController@showMatch', $this->match))
			->assertDontSee('<button class="btn btn-danger"');
	}

}
