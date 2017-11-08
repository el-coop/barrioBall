<?php

namespace Tests\Feature\Match;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\NotifyBeforeMatch;
use Carbon\Carbon;
use Notification;

class NotifyBeforeMatchTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $match;

    /**
     * @test
     * @group notifyBeforeMatch
     * @group match
     */

    public function test_notify_for_match_in_55_mins(): void
    {
        Notification::fake();
        $this->createManagedMatch(55);
        $this->artisan('match:notify');
        Notification::assertSentTo($this->user, NotifyBeforeMatch::class);

    }

    /**
     * @test
     * @group notifyBeforeMatch
     * @group match
     */

    public function test_notify_for_match_in_65_mins(): void
    {
        Notification::fake();
        $this->createManagedMatch(65);
        $this->artisan('match:notify');
        Notification::assertSentTo($this->user, NotifyBeforeMatch::class);

    }

    /**
     * @test
     * @group notifyBeforeMatch
     * @group match
     */

    public function test_dosent_notify_for_match_in_66_mins(): void
    {
        Notification::fake();
        $this->createManagedMatch(66);
        $this->artisan('match:notify');
        Notification::assertNotSentTo($this->user, NotifyBeforeMatch::class);

    }

    /**
     * @test
     * @group notifyBeforeMatch
     * @group match
     */

    public function test_dosent_notify_for_match_in_54_mins(): void
    {
        Notification::fake();
        $this->createManagedMatch(54);
        $this->artisan('match:notify');
        Notification::assertNotSentTo($this->user, NotifyBeforeMatch::class);

    }


    /**
     * @param int $minutes
     */
    protected function createManagedMatch(int $minutes): void
    {
        $this->user = factory(User::class)->create();
        $this->match = factory(Match::class)->create([
            'date_time' => Carbon::now()->addMinute($minutes),
        ]);
        $this->match->addPlayer($this->user);
    }

}
