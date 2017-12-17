<?php

namespace Tests\Feature\Match;

use App\Events\Match\ManageInvitationRejected;
use App\Events\Match\ManagerJoined;
use App\Events\Match\ManagersInvited;
use App\Listeners\Match\Cache\ClearManagersCache;
use App\Listeners\Match\Cache\ClearUserManagedMatches;
use App\Listeners\Match\Cache\ClearUserMatchManagerInvitation;
use App\Listeners\Match\Cache\ClearManagersPendingRequestCache;
use App\Listeners\Match\Cache\ClearUsersMatchManagerInvitations;
use App\Listeners\Match\SendManagerInvites;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\ManageInvitation;
use Cache;
use Event;
use Mockery;
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


	public function test_handles_managers_invitation(): void {
		Notification::fake();
		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->manager->id}_{$this->match->id}_manager"), Mockery::any())->andReturn(true);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->player->id}_{$this->match->id}_manager"), Mockery::any())->andReturn(false);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->extraPlayer->id}_{$this->match->id}_manager"), Mockery::any())->andReturn(false);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->player->id}_{$this->match->id}_managerInvitation"), Mockery::any())->andReturn(false);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->extraPlayer->id}_{$this->match->id}_managerInvitation"), Mockery::any())->andReturn(false);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("match_{$this->match->id}"), Mockery::any())->andReturn($this->match);
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_managerInvitation"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->extraPlayer->id}_{$this->match->id}_managerInvitation"));

		$this->actingAs($this->manager)->post(action('Match\MatchUserController@inviteManagers', $this->match), [
			'invite_managers' => [$this->player->id, $this->extraPlayer->id],
		])->assertSessionHas('alert', __('match/show.invitationSent', [], $this->manager->language));

		$this->assertTrue($this->match->hasManagerInvite($this->player));
		$this->assertTrue($this->match->hasManagerInvite($this->extraPlayer));

		Notification::assertSentTo($this->player, ManageInvitation::class);
		Notification::assertSentTo($this->extraPlayer, ManageInvitation::class);
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
	public function test_handles_manager_joining(): void {

		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->player->id}_{$this->match->id}_managerInvitation"), Mockery::any())->andReturn(true);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->player->id}_{$this->match->id}_joinRequest"), Mockery::any())->andReturn(false);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("match_{$this->match->id}"), Mockery::any())->andReturn($this->match);
		Cache::shouldReceive('tags')->once()->with("{$this->player->username}_managed")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->username}_hasManagedMatches"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_manager"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_managerInvitation"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->match->id}_managers"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->username}_requests"));

		$this->match->inviteManager($this->player);

		$this->actingAs($this->player)
			->post(action('Match\MatchUserController@joinAsManager', $this->match))
			->assertSessionHas('alert', __('match/show.managerJoined'));
		$this->assertFalse($this->match->hasManagerInvite($this->player));
		$this->assertTrue($this->match->hasManager($this->player));
		$this->assertFalse($this->match->hasPlayer($this->player));

	}

	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_handles_manager_joining_when_has_join_request(): void {

		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->player->id}_{$this->match->id}_managerInvitation"), Mockery::any())->andReturn(true);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->player->id}_{$this->match->id}_joinRequest"), Mockery::any())->andReturn(true);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->match->id}_isFull"), Mockery::any())->andReturn(false);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("match_{$this->match->id}"), Mockery::any())->andReturn($this->match);
		Cache::shouldReceive('tags')->once()->with("{$this->player->username}_managed")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');
		Cache::shouldReceive('tags')->once()->with("{$this->player->username}_played")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->username}_nextMatch"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_player"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_joinRequest"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->username}_hasManagedMatches"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_manager"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_managerInvitation"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->match->id}_managers"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->match->id}_joinRequests"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->match->id}_registeredPlayers"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->match->id}_isFull"));
		Cache::shouldReceive('forget')->twice()->with(sha1("{$this->player->username}_requests"));
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->manager->username}_requests"));

		$this->match->inviteManager($this->player);
		$this->match->addJoinRequest($this->player);

		$this->actingAs($this->player)
			->post(action('Match\MatchUserController@joinAsManager', $this->match))
			->assertSessionHas('alert', __('match/show.managerJoined'));
		$this->assertFalse($this->match->hasManagerInvite($this->player));
		$this->assertTrue($this->match->hasManager($this->player));
		$this->assertTrue($this->match->hasPlayer($this->player));

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
	public function test_invited_can_reject_join_management(): void {
		Event::fake();

		$this->match->inviteManager($this->player);

		$this->actingAs($this->player)
			->delete(action('Match\MatchUserController@rejectManageInvitation', $this->match))
			->assertSessionHas('alert', __('global.success'));
		$this->assertFalse($this->match->hasManagerInvite($this->player));
		$this->assertFalse($this->match->hasManager($this->player));

		Event::assertDispatched(ManageInvitationRejected::class, function ($event) {
			return $event->match->id == $this->match->id && $event->user->id == $this->player->id;
		});
	}


	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_handles_join_management_reject(): void {
		Cache::shouldReceive('rememberForever')->once()->with(sha1("{$this->player->id}_{$this->match->id}_managerInvitation"), Mockery::any())->andReturn(true);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("match_{$this->match->id}"), Mockery::any())->andReturn($this->match);
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->player->id}_{$this->match->id}_managerInvitation"));

		$this->match->inviteManager($this->player);

		$this->actingAs($this->player)
			->delete(action('Match\MatchUserController@rejectManageInvitation', $this->match))
			->assertSessionHas('alert', __('global.success'));
		$this->assertFalse($this->match->hasManagerInvite($this->player));
		$this->assertFalse($this->match->hasManager($this->player));

	}


	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_uninvited_can_reject_join_management(): void {
		Event::fake();

		$this->actingAs($this->player)
			->delete(action('Match\MatchUserController@rejectManageInvitation', $this->match))
			->assertStatus(403);

		Event::assertNotDispatched(ManageInvitationRejected::class);
	}



	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_manager_cant_reject_join_management(): void {
		Event::fake();

		$this->actingAs($this->manager)
			->delete(action('Match\MatchUserController@rejectManageInvitation', $this->match))
			->assertStatus(403);

		Event::assertNotDispatched(ManageInvitationRejected::class);
	}


	/**
	 * @test
	 * @group match
	 * @group inviteManagers
	 * @group management
	 */
	public function test_guest_cant_reject_join_management(): void {
		Event::fake();

		$this->delete(action('Match\MatchUserController@rejectManageInvitation', $this->match))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));

		Event::assertNotDispatched(ManageInvitationRejected::class);
	}

}
