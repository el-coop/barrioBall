<?php

namespace Tests\Browser\Match;

use App\Models\Admin;
use App\Models\Match;
use App\Models\Player;
use App\Models\User;
use Tests\Browser\Pages\Match\ShowPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RemovePlayerTest extends DuskTestCase {
	use DatabaseMigrations;

	protected $match;
	protected $manager;
	protected $player;

	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create();
		$this->manager = factory(User::class)->create();
		$this->player = factory(User::class)->create();
		$this->match->addManager($this->manager);
		$this->match->addPlayer($this->player);

	}

	/**
	 * @test
	 * @group removePlayer
	 * @group match
	 * @group showMatch
	 */
	public function test_manager_can_remove_player(): void {

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->manager)
				->visit(new ShowPage($this->match))
				->click('@player-remove')
				->fillModalMessage('I hate you')
				->assertSee(__('match/removePlayer.removed',[],$this->manager->language));
		});


		$this->assertFalse($this->player->inMatch($this->match));
		$this->assertFalse($this->match->hasJoinRequest($this->player));
	}
}

