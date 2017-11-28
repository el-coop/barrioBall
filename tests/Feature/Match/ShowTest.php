<?php

namespace Tests\Feature\Match;

use App\Models\Match;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
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
		$this->assertButtons($this->actingAs($this->manager)->get($this->match->url), 'match/show.joinRequest');
	}

	protected function assertButtons(TestResponse $response, string $see): void {
		collect([
			'match/show.matchEnded',
			'match/show.joinRequest',
			'match/show.login',
			'match/show.matchFull',
			'match/show.leaveMatch',
			'match/show.waitingForResponse',
			'match/show.repeatMatch',
		])->diff([$see])->each(function ($value, $index) use ($response) {
			$response->assertDontSeeText(__($value));
		});
		$response->assertSeeText(__($see));
	}

	/**
	 * @test
	 * @group match
	 * @group showMatch
	 * @group joinMatch
	 */
	public function test_finished_match_dont_show_buttons(): void {
		$this->match->date_time = Carbon::now()->subDay();
		$this->match->save();
		$this->assertButtons($this->actingAs($this->player)->get($this->match->url), 'match/show.matchEnded');
	}

	/**
	 * @test
	 * @group match
	 * @group showMatch
	 * @group joinMatch
	 */
	public function test_sent_request_sees_request_sent_button(): void {
		$this->match->addJoinRequest($this->player);
		$this->assertButtons($this->actingAs($this->player)->get($this->match->url), 'match/show.waitingForResponse');
	}


	/**
	 * @test
	 * @group match
	 * @group showMatch
	 * @group repeatMatch
	 */
	public function test_manager_sees_repeat_match_on_finished_match(): void {
		$this->match->date_time = Carbon::now()->subDay();
		$this->match->save();
		$this->assertButtons($this->actingAs($this->manager)->get($this->match->url), 'match/show.repeatMatch');
	}


	/**
	 * @test
	 * @group showMatch
	 */
	public function test_not_logged_sees_login_button(): void {
		$this->assertButtons($this->get($this->match->url), 'match/show.login');
	}

	/**
	 * @test
	 * @group showMatch
	 * @group leaveMatch
	 */
	public function test_joined_sees_leave_button(): void {
		$this->match->addPlayer($this->manager);
		$this->assertButtons($this->actingAs($this->manager)->get($this->match->url), 'match/show.leaveMatch');
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
		$this->assertButtons($this->actingAs($this->manager)->get($this->match->url), 'match/show.matchFull');
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
	 * @group match
	 * @group removePlayer
	 * @group showMatch
	 */
	public function test_shows_remove_button_to_manager(): void {
		$this->match->addPlayer($this->player);
		$this->actingAs($this->manager)
			->get(action('Match\MatchController@showMatch', $this->match))
			->assertSee("url: '" . action('Match\MatchUserController@removePlayer', $this->match) . "'")
			->assertSee('<button class="btn btn-danger"');
	}

	/**
	 * @test
	 * @group match
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
	 * @group match
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
	 * @group match
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

	/**
	 * @test
	 * @group match
	 * @group inviteManager
	 * @group showMatch
	 */
	public function test_doesnt_show_acceptInvitation_button_to_non_invited() {
		$this->actingAs($this->player)
			->get(action('Match\MatchController@showMatch', $this->match))
			->assertDontSee(__('match/show.acceptManageInvitation', [], $this->player->language));
	}

	/**
	 * @test
	 * @group match
	 * @group inviteManager
	 * @group showMatch
	 */
	public function test_doesnt_show_acceptInvitation_button_to_guest() {
		$this->get(action('Match\MatchController@showMatch', $this->match))
			->assertDontSee(__('match/show.acceptManageInvitation', [], $this->player->language));
	}

	/**
	 * @test
	 * @group match
	 * @group inviteManager
	 * @group showMatch
	 */
	public function test_doesnt_show_acceptInvitation_button_to_manager() {
		$this->actingAs($this->manager)
			->get(action('Match\MatchController@showMatch', $this->match))
			->assertDontSee(__('match/show.acceptManageInvitation', [], $this->player->language))
			->assertDontSee(__('match/show.accept', [], $this->player->language));
	}

	/**
	 * @test
	 * @group match
	 * @group inviteManager
	 * @group showMatch
	 */
	public function test_show_acceptInvitation_button_to_invited() {
		$this->match->inviteManager($this->player);
		$this->actingAs($this->player)
			->get(action('Match\MatchController@showMatch', $this->match))
			->assertSee(__('match/show.acceptManageInvitation', [], $this->player->language))
			->assertSee(__('match/show.accept', [], $this->player->language));
	}

}
