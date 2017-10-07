<?php

namespace Tests\Feature\Match;

use App\Events\Match\DeletedOldMatch;
use App\Listeners\Match\SendOldMatchDeletedMessage;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\OldMatchDeleted;
use Carbon\Carbon;
use Event;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteOldTest extends TestCase {
	use RefreshDatabase;

	protected $user;
	protected $match;

	/**
	 * @test
	 */
	public function test_doesnt_delete_not_old_matches(): void {
		$this->createManagedMatch();
		$this->artisan('match:deleteOld');

		$this->assertEquals(1, Match::count());
	}

	/**
	 * @param string $date - the date of the match
	 */
	protected function createManagedMatch(string $date = 'today'): void {
		$this->user = factory(User::class)->create();
		$this->match = factory(Match::class)->create([
			'date' => (new Carbon($date))->format('y/m/d'),
		]);
		$this->match->addManager($this->user);
	}

	/**
	 * @test
	 */
	public function test_deletes_old_matches(): void {
		Event::fake();

		$this->createManagedMatch('8 days ago');

		$this->artisan('match:deleteOld');
		$this->assertEquals(0, Match::count());

		Event::assertDispatched(DeletedOldMatch::class);
	}

	/**
	 * @test
	 */
	public function test_sends_old_match_deleted_notification(): void {
		Notification::fake();
		$this->createManagedMatch();

		$listener = new SendOldMatchDeletedMessage;
		$listener->handle(new DeletedOldMatch($this->match));

		Notification::assertSentTo($this->user, OldMatchDeleted::class);
	}
}
