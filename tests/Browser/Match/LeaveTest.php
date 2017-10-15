<?php

namespace Tests\Browser\Match;

use App\Models\Match;
use App\Models\User;
use Tests\Browser\Pages\Match\ShowPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LeaveTest extends DuskTestCase {
	use DatabaseMigrations;

	protected $match;
	protected $player;

	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create();
		$this->player = factory(User::class)->create();
		$this->match->addPlayer($this->player);
	}

	/**
	 * @test
	 * @group leaveMatch
	 * @group showMatch
	 * @group match
	 */
	public function test_user_can_leave_match(): void {

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->player)
				->visit(new ShowPage($this->match))
				->click('@leave-button')
				->assertSee(__('match/show.left', [], $this->player->language));
		});

		$this->assertFalse($this->match->hasPlayer($this->player));
		$this->assertFalse($this->match->hasJoinRequest($this->player));
	}

}
