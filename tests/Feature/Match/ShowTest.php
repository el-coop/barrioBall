<?php

namespace Tests\Feature\Match;

use App\Events\Match\ManagerLeft;
use App\Events\Match\MatchDeleted;
use App\Events\Match\UserJoined;
use App\Events\Match\UserLeft;
use App\Models\Admin;
use App\Models\Match;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowTest extends TestCase
{
	use DatabaseMigrations;

	protected $match;

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
	}

	public function test_shows_match_page()
	{
		$response = $this->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertStatus(200);
		$response->assertSee("<title>" . htmlentities ($this->match->name, ENT_QUOTES));
		$response->assertSee(htmlentities($this->match->address, ENT_QUOTES));
		$response->assertSee("{$this->match->date} {$this->match->time}");
		$response->assertSee("{$this->match->registeredPlayers()->count()}/{$this->match->players}");
	}

	public function test_login_link_for_unlogged(){
		$response = $this->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertSeeText(__('match/show.login'));
	}

	public function test_join_match(){
		$user = User::first();
		$response = $this->actingAs($user)->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertSeeText(__('match/show.joinRequest'));

		$this->actingAs($user)->expectsEvents(UserJoined::class)->post( action('Match\MatchUsersController@joinMatch', $this->match));

		$this->assertTrue($user->inMatch($this->match));
	}

	public function test_not_logged_cant_join_match(){
		$response = $this->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertDontSeeText(__('match/show.joinRequest'));

		$response = $this->post( action('Match\MatchUsersController@joinMatch', $this->match));
		$response->assertStatus(403);
	}

	public function test_joined_cant_join_match(){
		$user = User::first();
		$this->match->addPlayer($user);
		$response = $this->actingAs($user)->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertDontSeeText(__('match/show.joinRequest'));

		$response = $this->actingAs($user)->post( action('Match\MatchUsersController@joinMatch', $this->match));
		$response->assertStatus(403);
	}

	public function test_leave_match(){
		$user = User::first();
		$this->match->addPlayer($user);
		$response = $this->actingAs($user)->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertSeeText(__('match/show.leaveMatch'));

		$this->actingAs($user)->expectsEvents(UserLeft::class)->delete( action('Match\MatchUsersController@leaveMatch', $this->match));

		$this->assertFalse($user->inMatch($this->match));
	}

	public function test_unjoined_cant_leave_match(){
		$user = User::first();
		$response = $this->actingAs($user)->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertDontSeeText(__('match/show.leaveMatch'));

		$response = $this->actingAs($user)->delete( action('Match\MatchUsersController@leaveMatch', $this->match));

		$response->assertStatus(403);
	}

	public function test_not_logged_cant_leave_match(){
		$response = $this->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertDontSeeText(__('match/show.leaveMatch'));

		$response = $this->delete( action('Match\MatchUsersController@leaveMatch', $this->match));

		$response->assertStatus(403);
	}

	public function test_delete_match(){
		$user = User::first();
		$response = $this->actingAs($user)->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertSeeText(__('match/show.deleteMatch'));

		$this->actingAs($user)->expectsEvents(MatchDeleted::class)->delete( action('Match\MatchController@delete', $this->match));

		$this->assertDatabaseMissing('matches',['id' => $this->match->id]);
	}

	public function test_non_manager_cant_delete_match(){
		factory(Admin::class)->create()->each(function($user){
			$user->user()->save(factory(User::class)->make());
		});
		$extraUser = User::find(2);
		$response = $this->actingAs($extraUser)->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertDontSeeText(__('match/show.deleteMatch'));

		$response = $this->actingAs($extraUser)->delete( action('Match\MatchController@delete', $this->match));
		$response->assertStatus(403);
	}

	public function test_invite_managers(){
		$user = User::first();
		$response = $this->actingAs($user)->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertSeeText(__('match/show.inviteManagers'));

		$this->actingAs($user)->post( action('Match\MatchUsersController@inviteManagers', $this->match));
		//Todo - add testing when working on feature
	}

	public function test_non_manager_cant_invite_others(){
		factory(Admin::class)->create()->each(function($user){
			$user->user()->save(factory(User::class)->make());
		});
		$extraUser = User::find(2);
		$response = $this->actingAs($extraUser)->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertDontSeeText(__('match/show.inviteManagers'));

		$response = $this->actingAs($extraUser)->post( action('Match\MatchUsersController@inviteManagers', $this->match));
		$response->assertStatus(403);
	}

	public function test_leave_managment(){
		factory(Admin::class)->create()->each(function($user){
			$user->user()->save(factory(User::class)->make());
		});
		$extraManager = User::find(2);
		$this->match->addManager($extraManager);
		$user = User::first();

		$response = $this->actingAs($user)->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertSeeText(__('match/show.stopManaging'));

		$this->actingAs($user)->expectsEvents(ManagerLeft::class)->delete( action('Match\MatchUsersController@stopManaging', $this->match));
		$this->assertFalse($user->isManager($this->match));
	}

	public function test_last_manager_cant_leave_managment(){
		$user = User::first();

		$response = $this->actingAs($user)->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertDontSeeText(__('match/show.stopManaging'));

		$response = $this->actingAs($user)->delete( action('Match\MatchUsersController@stopManaging', $this->match));
		$response->assertStatus(403);
	}

	public function test_non_manager_cant_leave_managment(){
		factory(Admin::class)->create()->each(function($user){
			$user->user()->save(factory(User::class)->make());
		});
		$extraUser = User::find(2);

		$response = $this->actingAs($extraUser)->get(action('Match\MatchController@showMatch', $this->match));
		$response->assertDontSeeText(__('match/show.stopManaging'));

		$response = $this->actingAs($extraUser)->delete( action('Match\MatchUsersController@stopManaging', $this->match));
		$response->assertStatus(403);
	}
}
