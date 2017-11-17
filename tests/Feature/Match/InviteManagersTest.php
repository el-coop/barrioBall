<?php

namespace Tests\Feature\Match;

use App\Models\Match;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InviteManagersTest extends TestCase {
	use RefreshDatabase;

	protected $match;
	protected $manager;
	protected $player;


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
	 * @group inviteManagers
	 */

	public function test_manager_can_invite_managers(): void {
		$this->actingAs($this->manager)->post(action('Match\MatchUserController@inviteManagers', $this->match));
		//Todo - add testing when working on feature
		$this->assertTrue(true);
	}


	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 */
	public function test_non_manager_cant_invite_others(): void {
		$this->actingAs($this->player)
			->post(action('Match\MatchUserController@inviteManagers', $this->match))
			->assertStatus(403);
	}
}
