<?php

namespace Tests\Feature\Match;

use App\Events\Match\JoinRequestSent;
use App\Models\Match;
use App\Models\User;
use Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CancelJoinRequestTest extends TestCase
{
    use RefreshDatabase;

    protected $match;
    protected $player;
    protected $manager;

    public function setUp()
    {

        parent::setUp(); // TODO: Change the autogenerated stub
        $this->manager = factory(User::class)->create();
        $this->match = factory(Match::class)->create();
        $this->player = factory(User::class)->create();
        $this->match->addManager($this->manager);
    }

    /**
     *
     */
    public function test_cant_CancelJoinRequest_if_not_joind(): void
    {
        $this->actingAs($this->player)->post(action('Match\MatchUsersController@cancelJoin', $this->match), [])
            ->assertStatus(403);
    }

    /**
     *
     */
    public function test_can_cancel_after_joining(): void
    {
        $this->match->addJoinRequest($this->player);
        $this->actingAs($this->player)->post(action('Match\MatchUsersController@cancelJoin', $this->match), [])
            ->assertStatus(302)
            ->assertSessionHas('alert', __('match/show.cancelMessage'));

        $this->assertDatabaseMissing('join_match_requests', [
            'user_id' => $this->player->id,
            'match_id' => $this->match->id]);
    }

    /**
     *
     */
    public function test_can_rejoin_after_canceling(): void
    {
        $this->test_can_cancel_after_joining();
        Event::fake();

        $this->actingAs($this->player)->post(action('Match\MatchUsersController@joinMatch', $this->match), [
            'message' => 'bla',
        ])->assertStatus(302)
            ->assertSessionHas('alert', __('match/show.joinMatchSent'));

        $this->assertTrue($this->match->hasJoinRequest($this->player));

        Event::assertDispatched(JoinRequestSent::class, function ($event) {
            return $event->user->id === $this->player->id && $event->message = 'bla';
        });
    }
}
