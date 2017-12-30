<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\Match;
use App\Models\Player;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase {
	use RefreshDatabase;

	protected $user;
	protected $match;

	public function setUp() {
		parent::setUp();

		$this->user = factory(User::class)->create();
		$this->match = factory(Match::class)->create();
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_user_relationship(): void {

		$this->assertInstanceOf(MorphTo::class, $this->user->user());
		$this->assertInstanceOf(Player::class, $this->user->user);
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_isManager_return_true_when_manager(): void {
		$this->match->addManager($this->user);
		$this->assertTrue($this->user->isManager($this->match));
	}


	/**
	 * @test
	 * @group user
	 */
	public function test_isManager_return_false_when_not_manager(): void {
		$this->assertFalse($this->user->isManager($this->match));
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_managedMatches_relationship(): void {
		factory(Match::class, 3)->create()->each(function ($match) {
			$match->addPlayer($this->user);
		});
		$this->match->addManager($this->user);

		$this->assertInstanceOf(BelongsToMany::class, $this->user->managedMatches());
		$this->assertInstanceOf(Collection::class, $this->user->managedMatches);
		$this->assertArraySubset(Match::whereHas('managers', function ($query) {
			return $query->id = $this->user->id;
		})->get()->toArray(), $this->user->managedMatches->toArray());
		$this->assertCount(1, $this->user->managedMatches);
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_isAdmin_returns_false_when_user_is_not_admin(): void {
		$this->assertFalse($this->user->isAdmin());
	}


	/**
	 * @test
	 * @group user
	 */
	public function test_isAdmin_returns_true_when_user_is_admin(): void {
		$user = factory(User::class)->create([
			'user_id' => function () {
				return factory(Admin::class)->create()->id;
			},
			'user_type' => 'Admin',
		]);
		$this->assertTrue($user->isAdmin());
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_canJoin_returns_true_when_match_is_full_for_regular_user(): void {
		factory(User::class, $this->match->players)->create()->each(function ($player) {
			$this->match->addPlayer($player);
		});
		$this->assertTrue($this->user->canJoin($this->match));
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_canJoin_returns_true_when_match_is_full_for_manager_user(): void {
		factory(User::class, $this->match->players)->create()->each(function ($player) {
			$this->match->addPlayer($player);
		});
		$this->match->addManager($this->user);
		$this->assertFalse($this->user->canJoin($this->match));
	}


	/**
	 * @test
	 * @group user
	 */
	public function test_canJoin_returns_false_when_user_in_match(): void {
		$this->match->addPlayer($this->user);
		$this->assertFalse($this->user->canJoin($this->match));
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_canJoin_returns_false_when_user_has_join_request(): void {
		$this->match->addJoinRequest($this->user);
		$this->assertFalse($this->user->canJoin($this->match));
	}


	/**
	 * @test
	 * @group user
	 */
	public function test_canJoin_returns_true(): void {
		$this->assertTrue($this->user->canJoin($this->match));
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_inMatch_returns_false_user_not_in_match(): void {
		$this->assertFalse($this->user->inMatch($this->match));
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_inMatch_returns_true_user_in_match(): void {
		$this->match->addPlayer($this->user);
		$this->assertTrue($this->user->inMatch($this->match));
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_playerMatches_relationship(): void {
		factory(Match::class, 3)->create()->each(function ($match) {
			$match->addPlayer($this->user);
		});
		$this->match->addManager($this->user);

		$this->assertInstanceOf(BelongsToMany::class, $this->user->playedMatches());
		$this->assertInstanceOf(Collection::class, $this->user->playedMatches);
		$this->assertArraySubset(Match::whereHas('registeredPlayers', function ($query) {
			return $query->id = $this->user->id;
		})->get()->toArray(), $this->user->playedMatches->toArray());
		$this->assertCount(3, $this->user->playedMatches);
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_joinRequests_relationship(): void {
		factory(Match::class, 3)->create()->each(function ($match) {
			$match->addJoinRequest($this->user);
		});

		$this->assertInstanceOf(BelongsToMany::class, $this->user->joinRequests());
		$this->assertInstanceOf(Collection::class, $this->user->joinRequests);
		$this->assertArraySubset(Match::whereHas('joinRequests', function ($query) {
			return $query->id = $this->user->id;
		})->get()->toArray(), $this->user->joinRequests->toArray());
		$this->assertCount(3, $this->user->joinRequests);
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_sentRequest_returns_false_when_not_sent(): void {
		$this->assertFalse($this->user->sentRequest($this->match));
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_sentRequest_returns_true_when_sent(): void {
		$this->match->addJoinRequest($this->user);
		$this->assertTrue($this->user->sentRequest($this->match));
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_delete_deletes_morphed(): void {
		$this->user->delete();
		$this->assertDatabaseMissing('users', [
			'id' => $this->user->id,
		]);
		$this->assertDatabaseMissing('players', [
			'id' => $this->user->user->id,
		]);
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_cancelJoinRequest_delete_relationship(): void {
		$this->match->addJoinRequest($this->user);
		$this->match->cancelJoinRequest($this->user);
		$this->assertDatabaseMissing('join_match_requests', [
			'user_id' => $this->user->id,
			'match_id' => $this->match->id]);

	}

	/**
	 * @test
	 * @group user
	 */
	public function test_manageInvite_adds_invitation(): void {
		$this->user->manageInvite($this->match);
		$this->assertDatabaseHas('manager_invites', [
			'user_id' => $this->user->id,
			'match_id' => $this->match->id]);
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_manageInvites_relationship(): void {
		factory(Match::class, 3)->create()->each(function ($match) {
			$match->inviteManager($this->user);
		});

		$this->assertInstanceOf(BelongsToMany::class, $this->user->manageInvites());
		$this->assertInstanceOf(Collection::class, $this->user->manageInvites);
		$this->assertArraySubset(Match::whereHas('managerInvites', function ($query) {
			return $query->id = $this->user->id;
		})->get()->toArray(), $this->user->manageInvites->toArray());
		$this->assertCount(3, $this->user->manageInvites);
	}


	/**
	 * @test
	 * @group user
	 */
	public function test_hasManageInvite_returns_true_when_has_invite(): void {
		$this->match->inviteManager($this->user);
		$this->assertTrue($this->user->hasManageInvite($this->match));
	}


	/**
	 * @test
	 * @group user
	 */
	public function test_hasManageInvite_returns_false_when_doesnt_has_invite(): void {
		$this->assertFalse($this->user->hasManageInvite($this->match));
	}


	/**
	 * @test
	 * @group user
	 * @group admin
	 */

	public function test_makeAdmin_adds_admin(): void {
		$oldUser = $this->user->user;
		$this->user->makeAdmin();
		$this->assertTrue($this->user->isAdmin());
		$this->assertDatabaseMissing('players', [
			'id' => $oldUser->id,
		]);
	}


	/**
	 * @test
	 * @group user
	 * @group admin
	 */

	public function test_makeAdmin_throws_exception_when_already_admin(): void {
		$this->expectException(Exception::class);
		$user = factory(User::class)->create([
			'user_id' => function () {
				return factory(Admin::class)->create()->id;
			},
			'user_type' => 'Admin',
		]);
		$user->makeAdmin();
	}
}
