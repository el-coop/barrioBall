<?php

namespace Tests\Feature\Match;

use App\Events\Match\Edited;
use App\Listeners\Match\Cache\ClearMatchCache;
use App\Listeners\Match\SendEditedNotification;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\EditedNotification;
use Cache;
use Carbon\Carbon;
use Event;
use Mockery;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditTest extends TestCase {
	use RefreshDatabase;

	protected $match;
	protected $player;
	protected $manager;

	public function setUp() {
		parent::setUp();
		$this->manager = factory(User::class)->create();
		$this->match = factory(Match::class)->create();
		$this->player = factory(User::class)->create();
		$this->match->addManager($this->manager);
	}

	/**
	 * @test
	 * @group match
	 * @group editMatch
	 */
	public function test_doesnt_show_edit_match_page_to_guest(): void {
		$this->get(action('Match\MatchController@showEditForm', $this->match))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group match
	 * @group editMatch
	 */
	public function test_doesnt_show_edit_match_page_to_non_manager(): void {
		$this->actingAs($this->player)
			->get(action('Match\MatchController@showEditForm', $this->match))
			->assertStatus(403);
	}


	/**
	 * @test
	 * @group match
	 * @group editMatch
	 */
	public function test_shows_edit_match_page_to_manager(): void {
		$this->actingAs($this->manager)
			->get(action('Match\MatchController@showEditForm', $this->match))
			->assertSee("<title>" . __('match/edit.title', ['name' => htmlentities($this->match->name, ENT_QUOTES)]));
	}

	/**
	 * @test
	 * @group match
	 * @group editMatch
	 */
	public function test_manager_can_edit_match(): void {
		$date_time = $this->match->date_time;
		$date_time->addMinute();
		$date_time->addDay();
		$this->actingAs($this->manager)
			->patch(action('Match\MatchController@edit', $this->match), [
				'name' => 'Nurs Match',
				'address' => 'Test Test',
				'lat' => 0,
				'lng' => 0,
				'players' => 8,
				'date' => $this->match->date_time->addDay()->format('d/m/y'),
				'time' => $this->match->date_time->addMinute()->format('H:i'),
				'description' => 'description',
			])->assertRedirect(action('Match\MatchController@showMatch', $this->match))
			->assertSessionHas('alert', __('global.success'));

		$match = $this->match->fresh();
		$this->assertEquals('Nurs Match', $match->name);
		$this->assertEquals('Test Test', $match->address);
		$this->assertEquals(0, $match->lat);
		$this->assertEquals(0, $match->lng);
		$this->assertEquals(8, $match->players);
		$this->assertEquals($date_time->format('d/m/y'), $match->date);
		$this->assertEquals($date_time->format('H:i'), $match->time);
		$this->assertEquals('description', $match->description);
	}


	/**
	 * @test
	 * @group match
	 * @group editMatch
	 */
	public function test_validation(): void {
		$this->actingAs($this->manager)
			->patch(action('Match\MatchController@edit', $this->match))
			->assertSessionHasErrors([
				'name',
				'address',
				'lat',
				'lng',
				'players',
				'date',
				'time',
				'description',
			]);
	}

	/**
	 * @test
	 * @group match
	 * @group editMatch
	 */
	public function test_guest_cant_edit_match(): void {
		$this->patch(action('Match\MatchController@edit', $this->match), [
			'name' => 'Nurs Match',
			'address' => 'Test Test',
			'lat' => 0,
			'lng' => 0,
			'players' => 8,
			'date' => $this->match->date_time->addDay()->format('d/m/y'),
			'time' => $this->match->date_time->addMinute()->format('H:i'),
			'description' => 'description',
		])->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}


	/**
	 * @test
	 * @group match
	 * @group editMatch
	 */
	public function test_non_manager_cant_edit_match(): void {
		$this->actingAs($this->player)->patch(action('Match\MatchController@edit', $this->match), [
			'name' => 'Nurs Match',
			'address' => 'Test Test',
			'lat' => 0,
			'lng' => 0,
			'players' => 8,
			'date' => $this->match->date_time->addDay()->format('d/m/y'),
			'time' => $this->match->date_time->addMinute()->format('H:i'),
			'description' => 'description',
		])->assertStatus(403);
	}

	/**
	 * @test
	 * @group match
	 * @group editMatch
	 */
	public function test_event_dispatched_when_match_edited(): void {
		Event::fake();
		$this->actingAs($this->manager)
			->patch(action('Match\MatchController@edit', $this->match), [
				'name' => 'Nurs Match',
				'address' => 'Test Test',
				'lat' => 0,
				'lng' => 0,
				'players' => 8,
				'date' => Carbon::now()->addDay()->format('d/m/y'),
				'time' => Carbon::now()->addMinute()->format('H:i'),
				'description' => 'description',
			]);

		Event::assertDispatched(Edited::class, function ($event) {
			return $event->match->id == $this->match->id;
		});
	}

	/**
	 * @test
	 * @group match
	 * @group editMatch
	 */
	public function test_notification_dispached_on_event(): void {
		$this->match->addPlayer($this->player);
		Notification::fake();

		$listener = new SendEditedNotification;
		$listener->handle(new Edited($this->match));

		Notification::assertSentTo($this->manager, EditedNotification::class);
		Notification::assertSentTo($this->player, EditedNotification::class);
	}

	/**
	 * @test
	 * @group match
	 * @group editMatch
	 */
	public function test_match_cache_deleted_when_match_edited(): void {
		Event::fake();
		Cache::shouldReceive('rememberForever')->twice()->with(sha1("{$this->manager->id}_{$this->match->id}_manager"), Mockery::any())->andReturn(true);
		Cache::shouldReceive('rememberForever')->once()->with(sha1("match_{$this->match->id}"), Mockery::any())->andReturn($this->match);
		Cache::shouldReceive('forget')->once()->with(sha1("match_{$this->match->id}"));

		$this->actingAs($this->manager)
			->patch(action('Match\MatchController@edit', $this->match), [
				'name' => 'Nurs Match',
				'address' => 'Test Test',
				'lat' => 0,
				'lng' => 0,
				'players' => 8,
				'date' => Carbon::now()->addDay()->format('d/m/y'),
				'time' => Carbon::now()->addMinute()->format('H:i'),
				'description' => 'description',
			]);

	}

}
