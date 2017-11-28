<?php

namespace Tests\Unit\Models;

use App\Models\Match;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatchTest extends TestCase {
	use RefreshDatabase;


	protected $match;
	protected $player;

	public function setUp() {
		parent::setUp();

		$this->match = factory(Match::class)->create();
		$this->player = factory(User::class)->create();

	}

	/**
	 * @test
	 * @group match
	 */
	public function test_addManager_adds_manager(): void {
		$this->match->addManager($this->player);
		$this->assertDatabaseHas('match_user', [
			'user_id' => $this->player->id,
			'match_id' => $this->match->id,
			'role' => 'manager',
		]);
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_addPlayer_adds_player(): void {
		$this->match->addPlayer($this->player);
		$this->assertDatabaseHas('match_user', [
			'user_id' => $this->player->id,
			'match_id' => $this->match->id,
			'role' => 'player',
		]);
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_addUser_adds_manager(): void {
		$this->match->addUser($this->player, true);
		$this->assertDatabaseHas('match_user', [
			'user_id' => $this->player->id,
			'match_id' => $this->match->id,
			'role' => 'manager',
		]);

	}

	/**
	 * @test
	 * @group match
	 */
	public function test_addUser_adds_player(): void {
		$this->match->addUser($this->player);
		$this->assertDatabaseHas('match_user', [
			'user_id' => $this->player->id,
			'match_id' => $this->match->id,
			'role' => 'player',
		]);
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_users_relationship(): void {
		$users = factory(User::class, 5)->create()->each(function ($user) {
			$this->match->addUser($user);
		});
		$this->assertInstanceOf(BelongsToMany::class, $this->match->users());
		$this->assertInstanceOf(Collection::class, $this->match->users);
		$this->assertArraySubset($users->toArray(), $this->match->users->toArray());
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_removeManager_removes_manager(): void {
		$this->match->addManager($this->player);
		$this->match->removeManager($this->player);
		$this->assertDatabaseMissing('match_user', [
			'user_id' => $this->player->id,
			'match_id' => $this->match->id,
			'role' => 'manager',
		]);
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_removePlayer_removes_player(): void {
		$this->match->addPlayer($this->player);
		$this->match->removePlayer($this->player);
		$this->assertDatabaseMissing('match_user', [
			'user_id' => $this->player->id,
			'match_id' => $this->match->id,
			'role' => 'player',
		]);
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_managers_relationship(): void {
		factory(User::class, 2)->create()->each(function ($user) {
			$this->match->addUser($user);
		});
		$managers = factory(User::class, 2)->create()->each(function ($user) {
			$this->match->addManager($user);
		});

		$this->assertInstanceOf(BelongsToMany::class, $this->match->managers());
		$this->assertInstanceOf(Collection::class, $this->match->managers);
		$this->assertArraySubset($managers->toArray(), $this->match->managers->toArray());
		$this->assertCount(2, $this->match->managers);
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_registeredPlayers_relationship(): void {
		$players = factory(User::class, 2)->create()->each(function ($user) {
			$this->match->addUser($user);
		});
		factory(User::class, 2)->create()->each(function ($user) {
			$this->match->addManager($user);
		});

		$this->assertInstanceOf(BelongsToMany::class, $this->match->registeredPlayers());
		$this->assertInstanceOf(Collection::class, $this->match->registeredPlayers);
		$this->assertArraySubset($players->toArray(), $this->match->registeredPlayers->toArray());
		$this->assertCount(2, $this->match->registeredPlayers);
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_get_url_attribute(): void {
		$this->assertEquals(action('Match\MatchController@showMatch',$this->match),$this->match->url);
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_addJoinRequest_adds_join_request(): void {
		$this->match->addJoinRequest($this->player);
		$this->assertDatabaseHas('join_match_requests', [
			'user_id' => $this->player->id,
			'match_id' => $this->match->id,
		]);
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_hasPlayer_returns_true_when_it_has_player(): void {

		$this->match->addPlayer($this->player);
		$this->assertTrue($this->match->hasPlayer($this->player));
	}


	/**
	 * @test
	 * @group match
	 */
	public function test_hasPlayer_returns_false_when_it_doesnt_have_player(): void {

		$this->assertFalse($this->match->hasPlayer($this->player));
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_hasManager_returns_true_when_it_has_manager(): void {

		$this->match->addManager($this->player);
		$this->assertTrue($this->match->hasMAnager($this->player));
	}


	/**
	 * @test
	 * @group match
	 */
	public function test_hasManager_returns_false_when_it_doesnt_have_manager(): void {

		$this->assertFalse($this->match->hasManager($this->player));
	}


	/**
	 * @test
	 * @group match
	 */
	public function test_hasJoinRequest_returns_true_when_it_has_join_request(): void {

		$this->match->addJoinRequest($this->player);
		$this->assertTrue($this->match->hasJoinRequest($this->player));
	}


	/**
	 * @test
	 * @group match
	 */
	public function test_hasJoinRequest_returns_false_when_it_doesnt_have_join_request(): void {

		$this->assertFalse($this->match->hasJoinRequest($this->player));
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_isFull_returns_false_when_it_has_space(): void {

		$this->assertFalse($this->match->isFull());
	}


	/**
	 * @test
	 * @group match
	 */
	public function test_isFull_returns_true_when_it_is_full(): void {
		factory(User::class, $this->match->players)->create()->each(function ($player) {
			$this->match->addPlayer($player);
		});
		$this->assertTrue($this->match->isFull());
	}


	/**
	 * @test
	 * @group match
	 */
	public function test_joinRequests_relationship(): void {
		$players = factory(User::class, 2)->create()->each(function ($user) {
			$this->match->addJoinRequest($user);
		});

		$this->assertInstanceOf(BelongsToMany::class, $this->match->joinRequests());
		$this->assertInstanceOf(Collection::class, $this->match->joinRequests);
		$this->assertArraySubset($players->toArray(), $this->match->joinRequests->toArray());
		$this->assertCount(2, $this->match->joinRequests);
	}


	/**
	 * @test
	 * @group match
	 */
	public function test_ended_return_true_when_ended(): void {
		$this->match->date_time = new Carbon('yesterday');
		$this->match->save();
		$match = Match::first();

		$this->assertTrue($match->ended());
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_ended_return_false_when_not_ended(): void {
		$this->match->date_time = new Carbon('tomorrow');
		$this->match->save();
		$match = Match::first();

		$this->assertFalse($match->ended());
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_managerInvites_relationship(): void {
		$players = factory(User::class, 2)->create()->each(function ($user) {
			$this->match->inviteManager($user);
		});

		$this->assertInstanceOf(BelongsToMany::class, $this->match->managerInvites());
		$this->assertInstanceOf(Collection::class, $this->match->managerInvites);
		$this->assertArraySubset($players->toArray(), $this->match->managerInvites->toArray());
		$this->assertCount(2, $this->match->managerInvites);
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_inviteManager_invites_Manager(): void {
		$this->match->inviteManager($this->player);
		$this->assertTrue($this->match->hasManagerInvite($this->player));
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_hasManagerInvite_returns_true_when_it_has_manager_invite(): void {
		$this->match->inviteManager($this->player);
		$this->assertTrue($this->match->hasManagerInvite($this->player));
	}


	/**
	 * @test
	 * @group match
	 */
	public function test_hasManagerInvite_returns_false_when_itdoesnt_have_manager_invite(): void {
		$this->assertFalse($this->match->hasManagerInvite($this->player));
	}

	/**
	 * @test
	 * @group match
	 */
	public function test_removeManageInvitation_removes_invitation(): void {
		$this->match->inviteManager($this->player);
		$this->match->removeManageInvitation($this->player);
		$this->assertFalse($this->match->hasManagerInvite($this->player));
	}

}

