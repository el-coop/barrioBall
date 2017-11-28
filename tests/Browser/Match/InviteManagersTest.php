<?php

namespace Tests\Browser\Match;

use App\Models\Match;
use App\Models\User;
use Tests\Browser\Pages\Match\ShowPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class InviteManagersTest extends DuskTestCase
{
	use DatabaseMigrations;

	protected $match;
	protected $manager;
	protected $player;

	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create();
		$this->player = factory(User::class)->create([
			'username' => 'player'
		]);
		$this->manager = factory(User::class)->create();
		$this->match->addManager($this->manager);
	}


	/**
	 * @test
	 * @group inviteManagers
	 * @group match
	 * @group showMatch
	 */
	public function test_can_invite_managers(): void {

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->manager)
				->visit(new ShowPage($this->match))
				->click('@invite-managers-button')
				->type('.v-select .form-control','pla')
				->waitFor('.v-select .dropdown-menu')
				->click('.v-select .dropdown-menu')
				->press('.modal-body .btn.btn-info.btn-block > .fa-plus-circle')
				->assertSee(__('match/show.invitationSent',[],$this->manager->language));
		});

		$this->assertTrue($this->player->hasManageInvite($this->match));
	}

	/**
	 * @test
	 * @group inviteManagers
	 * @group match
	 * @group showMatch
	 */
	public function test_can_accept_invitation(): void {

		$this->match->inviteManager($this->player);

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->player)
				->visit(new ShowPage($this->match))
				->click('.btn-group .btn.btn-outline-success')
				->assertSee(__('match/show.managerJoined',[],$this->player->language));
		});

		$this->assertTrue($this->match->hasManager($this->player));

	}


}
