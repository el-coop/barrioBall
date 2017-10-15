<?php

namespace Tests\Feature\Match;

use App\Events\Match\MatchDeleted;
use App\Listeners\Match\SendMatchDeletedNotification;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\Deleted;
use Event;
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
	public function test_notification_sent_when_matchDeleted_dispatched(): void {
		Notification::fake();

		$listener = new SendMatchDeletedNotification;
		$listener->handle(new MatchDeleted($this->manager, $this->match));

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
