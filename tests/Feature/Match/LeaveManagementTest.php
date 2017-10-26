<?php

namespace Tests\Feature\Match;

use App\Events\Match\ManagerLeft;
use App\Listeners\Match\SendManagerLeftNotification;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\ManagerLeft as ManagerLeftNotification;
use Event;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaveManagementTest extends TestCase {
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
	 * @group leaveManagement
	 * @group management
	 */
	public function test_leave_managment(): void {
		Event::fake();
		$this->match->addManager($this->player);
		$this->actingAs($this->player)
			->delete(action('Match\MatchUsersController@stopManaging', $this->match))
			->assertSessionHas('alert', __('match/show.managementLeft'));
		$this->assertFalse($this->player->isManager($this->match));
		Event::assertDispatched(ManagerLeft::class);
	}

	/**
	 * @test
	 * @group match
	 * @group leaveManagement
	 * @group management
	 */
	public function test_manager_left_notification_sent(): void {
		Notification::fake();

		$listener = new SendManagerLeftNotification();
		$listener->handle(new ManagerLeft($this->match, $this->player));

		Notification::assertSentTo($this->manager, ManagerLeftNotification::class);
	}

	/**
	 * @test
	 * @group match
	 * @group leaveManagement
	 * @group management
	 */
	public function test_last_manager_cant_leave_managment(): void {
		Event::fake();

		$this->actingAs($this->manager)->delete(action('Match\MatchUsersController@stopManaging', $this->match))
			->assertStatus(302)->assertSessionHasErrors('managers');

		Event::assertNotDispatched(ManagerLeft::class);
	}

	/**
	 * @test
	 * @group match
	 * @group leaveManagement
	 * @group management
	 */
	public function test_non_manager_cant_leave_managment(): void {
		Event::fake();

		$this->actingAs($this->player)->delete(action('Match\MatchUsersController@stopManaging', $this->match))
			->assertStatus(403);
		Event::assertNotDispatched(ManagerLeft::class);
	}

	/**
	 * @test
	 * @group match
	 * @group leaveManagement
	 * @group management
	 */
	public function test_not_logged_in_cant_leave_managment(): void {
		Event::fake();

		$this->delete(action('Match\MatchUsersController@stopManaging', $this->match))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
		Event::assertNotDispatched(ManagerLeft::class);
	}
}
