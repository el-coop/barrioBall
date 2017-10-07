<?php

namespace Tests\Unit\Models;

use App\Models\Match;
use App\Models\User;
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
	 */
	public function test_addJoinRequest_adds_join_request(): void {
		$this->match->addJoinRequest($this->player);
		$this->assertDatabaseHas('join_match_requests', [
			'user_id' => $this->player->id,
			'match_id' => $this->match->id
		]);

	}

	/**
	 * @test
	 */
	public function test_hasPlayer_returns_true_when_it_has_player(): void {

		$this->match->addPlayer($this->player);
		$this->assertTrue($this->match->hasPlayer($this->player));
	}


	/**
	 * @test
	 */
	public function test_hasPlayer_returns_false_when_it_doesnt_have_player(): void {

		$this->assertFalse($this->match->hasPlayer($this->player));
	}


	/**
	 * @test
	 */
	public function test_hasJoinRequest_returns_true_when_it_has_join_request(): void {

		$this->match->addJoinRequest($this->player);
		$this->assertTrue($this->match->hasJoinRequest($this->player));
	}


	/**
	 * @test
	 */
	public function test_hasJoinRequest_returns_false_when_it_doesnt_have_join_request(): void {

		$this->assertFalse($this->match->hasJoinRequest($this->player));
	}
}
