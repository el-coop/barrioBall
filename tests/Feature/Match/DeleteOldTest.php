<?php

namespace Tests\Feature\Match;

use App\Models\Admin;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\OldMatchDeleted;
use Carbon\Carbon;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteOldTest extends TestCase
{
	use RefreshDatabase;

	public function test_doesnt_delete_not_old_matches()
	{
		factory(Match::class)->create([
			'date' => date('y/m/d')
		]);
		$this->artisan('match:deleteOld');

		$this->assertEquals(1,Match::count());
	}

	public function test_deletes_old_matches()
	{
		Notification::fake();
		factory(Admin::class)->create()->each(function ($user) {
			$user->user()->save(factory(User::class)->make());
		});

		$match = factory(Match::class)->create([
			'date' => (new Carbon('8 days ago'))->format('y/m/d')
		]);
		$match->addManager(User::first());
		$this->artisan('match:deleteOld');
		$this->assertEquals(0,Match::count());

		Notification::assertSentTo(User::first(), OldMatchDeleted::class);
	}
}
