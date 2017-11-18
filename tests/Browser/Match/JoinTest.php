<?php

namespace Tests\Browser\Match;

use App\Models\Match;
use App\Models\User;
use Tests\Browser\Pages\Match\ShowPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class JoinTest extends DuskTestCase
{
	use DatabaseMigrations;

	protected $match;
	protected $manager;
	protected $player;

	public function setUp() {
		parent::setUp();

		$this->match = factory(Match::class)->create();
		$this->player = factory(User::class)->create();
		$this->manager = factory(User::class)->create();
		$this->match->addManager($this->manager);
	}

	/**
	 * @test
	 * @group joinMatch
	 * @group match
	 * @group showMatch
	 */
	public function test_manager_auto_joins(): void {

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->manager)
				->visit(new ShowPage($this->match))
				->click('@join-button')
				->assertSee(__('match/show.joined',[],$this->manager->language));
		});

		$this->assertTrue($this->match->hasPlayer($this->manager));
		$this->assertFalse($this->match->hasJoinRequest($this->manager));
	}

	/**
	 * @test
	 * @group joinMatch
	 * @group match
	 * @group showMatch
	 */
	public function test_player_can_send_join_request(): void {

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->player)
				->visit(new ShowPage($this->match))
				->click('@join-button')
				->fillModalMessage('Please let me join')
				->assertSee(__('match/show.joinMatchSent',[],$this->player->language));
		});

		$this->assertFalse($this->match->hasPlayer($this->player));
		$this->assertTrue($this->match->hasJoinRequest($this->player));
	}

	/**
	 * @test
	 * @group joinMatch
	 * @group match
	 * @group showMatch
	 */
	public function test_manager_can_accept_join_request(): void {
		$this->match->addJoinRequest($this->player);

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->manager)
				->visit(new ShowPage($this->match))
				->click('@accept-button')
				->fillModalMessage('You are welcome')
				->assertSee(__('match/requests.accepted',[],$this->manager->language));
		});

		$this->assertTrue($this->match->hasPlayer($this->player));
		$this->assertFalse($this->match->hasJoinRequest($this->player));
	}

	/**
	 * @test
	 * @group joinMatch
	 * @group match
	 * @group showMatch
	 */
	public function test_manager_cant_see_accept_join_request_full_match(): void {
		factory(User::class,$this->match->players)->create()->each(function($player){
			$this->match->addPlayer($player);
		});
		$this->match->addJoinRequest($this->player);

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->manager)
				->visit(new ShowPage($this->match))
				->assertMissing('@accept-button');
		});
	}

	/**
	 * @test
	 * @group joinMatch
	 * @group match
	 * @group showMatch
	 */
	public function test_manager_can_reject_join_request(): void {
		$this->match->addJoinRequest($this->player);

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->manager)
				->visit(new ShowPage($this->match))
				->click('@reject-button')
				->fillModalMessage('I hate you')
				->assertSee(__('match/requests.rejected',[],$this->manager->language));
		});

		$this->assertFalse($this->match->hasPlayer($this->player));
		$this->assertFalse($this->match->hasJoinRequest($this->player));
	}

}
