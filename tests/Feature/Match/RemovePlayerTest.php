<?php

namespace Tests\Feature\Match;

use App\Events\Match\PlayerRemoved;
use App\Listeners\Match\SendPlayerRemovedNotification;
use App\Models\Admin;
use App\Models\Match;
use App\Models\Player;
use App\Models\User;
use App\Notifications\Match\PlayerRemoved as PlayerRemovedNotification;
use Event;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemovePlayerTest extends TestCase {
	use RefreshDatabase;

	protected $match;
	protected $admin;
	protected $player;

	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create();
		factory(Admin::class)->create();
		$this->admin = factory(User::class)->create([
			'user_id' => Admin::first()->id,
			'user_type' => 'Admin'
		]);
		factory(Player::class)->create();
		$this->player = factory(User::class)->create([
			'user_id' => Player::first()->id,
			'user_type' => 'Player'
		]);
		$this->match->addManager($this->admin);
		$this->match->addPlayer($this->admin);
		$this->match->addPlayer($this->player);
	}

	public function test_shows_remove_button_to_manager() {
		$this->actingAs($this->admin)
			->get(action('Match\MatchController@showMatch', $this->match))
			->assertSee('ref="removeUser"')
			->assertSee('<button class="btn btn-danger"');
	}

	public function test_doesnt_show_remove_button_to_player(){
		$this->actingAs($this->player)
			->get(action('Match\MatchController@showMatch', $this->match))
			->assertDontSee('ref="removeUser"')
			->assertDontSee('<button class="btn btn-danger"');
	}


	public function test_doesnt_show_remove_button_to_guest(){
		$this->actingAs($this->player)
			->get(action('Match\MatchController@showMatch', $this->match))
			->assertDontSee('ref="removeUser"')
			->assertDontSee('<button class="btn btn-danger"');
	}

	public function test_doesnt_show_remove_button_on_manager(){
		$this->match->removePlayer($this->player);
		$this->actingAs($this->admin)
			->get(action('Match\MatchController@showMatch', $this->match))
			->assertDontSee('<button class="btn btn-danger"');
	}

	public function test_manager_can_kick_user_out(){
		Event::fake();

		$this->actingAs($this->admin)->delete(action('Match\MatchUsersController@removePlayer',$this->match),[
			'user' => $this->player->id,
			'message' => 'I hate you'
		]);

		$this->assertFalse($this->player->inMatch($this->match));

		Event::assertDispatched(PlayerRemoved::class, function ($event) {
			return $event->user->id === $this->player->id;
		});
	}

	public function test_player_cant_kick_user_out(){
		Event::fake();

		$this->actingAs($this->player)->delete(action('Match\MatchUsersController@removePlayer',$this->match),[
			'user' => $this->player->id,
			'message' => 'I hate you'
		]);

		$this->assertTrue($this->player->inMatch($this->match));

		Event::assertNotDispatched(PlayerRemoved::class, function ($event) {
			return $event->user->id === $this->player->id;
		});
	}

	public function test_guest_cant_kick_user_out(){
		Event::fake();

		$this->actingAs($this->player)->delete(action('Match\MatchUsersController@removePlayer',$this->match),[
			'user' => $this->player->id,
			'message' => 'I hate you'
		]);

		$this->assertTrue($this->player->inMatch($this->match));

		Event::assertNotDispatched(PlayerRemoved::class, function ($event) {
			return $event->user->id === $this->player->id;
		});
	}

	public function test_sends_notification_on_user_removed_event(){
		Notification::fake();

		$listener = new SendPlayerRemovedNotification();
		$listener->handle(new PlayerRemoved($this->player, Match::first(),''));

		Notification::assertSentTo($this->player, PlayerRemovedNotification::class);

	}
}
