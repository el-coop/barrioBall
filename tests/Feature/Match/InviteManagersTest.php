<?php

namespace Tests\Feature\Match;

use App\Events\Match\ManagerJoined;
use App\Events\Match\ManagersInvited;
use App\Listeners\Match\Cache\ClearManagersCache;
use App\Listeners\Match\Cache\ClearUserManagedMatches;
use App\Listeners\Match\Cache\ClearUserMatchManagerInvitation;
use App\Listeners\Match\Cache\ClearUsersMatchManagerInvitations;
use App\Listeners\Match\SendManagerInvites;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\ManageInvitation;
use Cache;
use Event;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InviteManagersTest extends TestCase {
	use RefreshDatabase;

	protected $match;
	protected $manager;
	protected $player;
	protected $extraPlayer;


	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create();
		$this->manager = factory(User::class)->create();
		$this->player = factory(User::class)->create();
		$this->extraPlayer = factory(User::class)->create();


		$this->match->addManager($this->manager);
	}

	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */

	public function test_manager_can_invite_managers(): void {
		Event::fake();

		$this->actingAs($this->manager)->post(action('Match\MatchUserController@inviteManagers', $this->match), [
			'invite_managers' => [$this->player->id, $this->extraPlayer->id],
		])->assertSessionHas('alert', __('match/show.invitationSent', [], $this->manager->language));

		$this->assertTrue($this->match->hasManagerInvite($this->player));
		$this->assertTrue($this->match->hasManagerInvite($this->extraPlayer));

		Event::assertDispatched(ManagersInvited::class, function ($event) {
			return $event->match->id == $this->match->id && $event->managers->diff([$this->player, $this->extraPlayer])->count() == 0;
		});
	}

	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */

	public function test_cant_invite_an_existing_managers(): void {
		Event::fake();
		$this->match->addManager($this->player);

		$this->actingAs($this->manager)->post(action('Match\MatchUserController@inviteManagers', $this->match), [
			'invite_managers' => [$this->player->id],
		])->assertSessionHas('alert', __('match/show.invitationSent', [], $this->manager->language));

		$this->assertFalse($this->match->hasManagerInvite($this->player));

		Event::assertNotDispatched(ManagersInvited::class, function ($event) {
			return $event->managers->search($this->player);
		});

	}

	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */

	public function test_cant_invite_already_invited_user(): void {
		Event::fake();
		$this->match->inviteManager($this->player);

		$this->actingAs($this->manager)->post(action('Match\MatchUserController@inviteManagers', $this->match), [
			'invite_managers' => [$this->player->id],
		])->assertSessionHas('alert', __('match/show.invitationSent', [], $this->manager->language));

		Event::assertNotDispatched(ManagersInvited::class, function ($event) {
			return $event->managers->search($this->player);
		});
	}


	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_manager_invitation_message_sent(): void {
		Notification::fake();

		$listener = new SendManagerInvites;
		$listener->handle(new ManagersInvited($this->match, collect([$this->player, $this->extraPlayer])));

		Notification::assertSentTo($this->player, ManageInvitation::class);
		Notification::assertSentTo($this->extraPlayer, ManageInvitation::class);

	}

	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_manager_invitation_cache_clears(): void {

		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_managerInvitation"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->extraPlayer->id}_{$this->match->id}_managerInvitation"));

		$listener = new ClearUsersMatchManagerInvitations;
		$listener->handle(new ManagersInvited($this->match, collect([$this->player, $this->extraPlayer])));
	}


	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_non_manager_cant_invite_managers(): void {
		Event::fake();

		$this->actingAs($this->player)
			->post(action('Match\MatchUserController@inviteManagers', $this->match))
			->assertStatus(403);

		$this->assertFalse($this->match->hasManagerInvite($this->player));
		Event::assertNotDispatched(ManagersInvited::class);
	}

	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_guest_cant_invite_managers(): void {
		Event::fake();

		$this->post(action('Match\MatchUserController@inviteManagers', $this->match))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
		$this->assertFalse($this->match->hasManagerInvite($this->player));

		Event::assertNotDispatched(ManagersInvited::class);
	}

	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_invited_can_join_management(): void {
		Event::fake();

		$this->match->inviteManager($this->player);

		$this->actingAs($this->player)
			->post(action('Match\MatchUserController@joinAsManager', $this->match))
			->assertSessionHas('alert', __('match/show.managerJoined'));
		$this->assertFalse($this->match->hasManagerInvite($this->player));
		$this->assertTrue($this->match->hasManager($this->player));

		Event::assertDispatched(ManagerJoined::class, function ($event) {
			return $event->match->id == $this->match->id && $event->user->id == $this->player->id;
		});
	}

	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_uninvited_cant_join_management(): void {
		Event::fake();

		$this->actingAs($this->player)
			->post(action('Match\MatchUserController@joinAsManager', $this->match))
			->assertStatus(403);
		$this->assertFalse($this->match->hasManager($this->player));

		Event::assertNotDispatched(ManagerJoined::class);
	}

	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_manager_cant_join_management(): void {
		Event::fake();

		$this->actingAs($this->manager)
			->post(action('Match\MatchUserController@joinAsManager', $this->match))
			->assertStatus(403);
		$this->assertFalse($this->match->hasManager($this->player));

		Event::assertNotDispatched(ManagerJoined::class);
	}


	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_guest_cant_join_management(): void {
		Event::fake();

		$this->post(action('Match\MatchUserController@joinAsManager', $this->match))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));

		Event::assertNotDispatched(ManagerJoined::class);
	}

	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_user_managed_matches_cache_clears_when_joins_management(): void {

		Cache::shouldReceive('tags')->once()->with("{$this->player->username}_managed")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');

		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->username}_hasManagedMatches"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_manager"));


		$listener = new ClearUserManagedMatches;
		$listener->handle(new ManagerJoined($this->match, $this->player));
	}

	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_user_manage_invitation_cache_clears_when_joins_management(): void {

		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_managerInvitation"));


		$listener = new ClearUserMatchManagerInvitation;
		$listener->handle(new ManagerJoined($this->match, $this->player));
	}

	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_match_manager_cache_clears_when_joins_management(): void {

		Cache::shouldReceive('forget')->once()->with(sha1("{$this->match->id}_managers"));

		$listener = new ClearManagersCache;
		$listener->handle(new ManagerJoined($this->match, $this->player));
	}
}
