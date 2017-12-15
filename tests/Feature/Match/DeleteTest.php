<?php

namespace Tests\Feature\Match;

use App\Events\Match\MatchDeleted;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\Deleted;
use Cache;
use Event;
use Mockery;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTest extends TestCase {
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
		$this->match->addPlayer($this->player);
	}


	/**
	 * @test
	 * @group match
	 * @group deleteMatch
	 */
	public function test_manager_can_delete_match(): void {
		Event::fake();

		$this->actingAs($this->manager)
			->delete(action('Match\MatchController@delete', $this->match));

		$this->assertDatabaseMissing('matches', ['id' => $this->match->id]);
		Event::assertDispatched(MatchDeleted::class);
	}

	/**
	 * @test
	 * @group match
	 * @group deleteMatch
	 */
	public function test_handles_deleted_match(): void {
		Notification::fake();
		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->manager->id}_{$this->match->id}_manager"), Mockery::any())->andReturn(true);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("match_{$this->match->id}"), Mockery::any())->andReturn($this->match);
		Cache::shouldReceive('tags')->once()->with("{$this->manager->username}_managed")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->manager->username}_hasManagedMatches"));

		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->username}_nextMatch"));
		Cache::shouldReceive('tags')->once()->with("{$this->player->username}_played")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');
		Cache::shouldReceive('tags')->once()->with("admin_matches")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->manager->username}_requests"));
		Cache::shouldReceive('forget')->once()->with(sha1("match_{$this->match->id}"));

		$this->actingAs($this->manager)
			->delete(action('Match\MatchController@delete', $this->match));

		$this->assertDatabaseMissing('matches', ['id' => $this->match->id]);
		Notification::assertSentTo($this->manager, Deleted::class);
		Notification::assertSentTo($this->player, Deleted::class);
	}


	/**
	 * @test
	 * @group match
	 * @group deleteMatch
	 */
	public function test_non_manager_cant_delete_match(): void {
		Event::fake();
		$this->actingAs($this->player)->delete(action('Match\MatchController@delete', $this->match))
			->assertStatus(403);

		Event::assertNotDispatched(MatchDeleted::class);
	}
}
