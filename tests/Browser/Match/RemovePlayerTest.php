<?php

namespace Tests\Browser\Match;

use App\Models\Admin;
use App\Models\Match;
use App\Models\Player;
use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RemovePlayerTest extends DuskTestCase {
	use DatabaseMigrations;

	protected $match;
	protected $admin;
	protected $player;

	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create();
		$this->admin = factory(User::class)->create([
			'user_id' => function () {
				return factory(Admin::class)->create();
			},
			'user_type' => 'Admin',
		]);
		$this->player = factory(User::class)->create();
		$this->match->addManager($this->admin);
		$this->match->addPlayer($this->admin);
		$this->match->addPlayer($this->player);

	}

	public function test_remove_player_process_works() {

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->admin)
				->visit(action('Match\MatchController@showMatch', $this->match))
				->click('.list-group-item > .btn.btn-danger')
				->waitFor('#listsModal .form-control',2)
				->type('#listsModal .form-control', 'I hate you')
				->press('#listsModal .btn.btn-danger.btn-block')
				->assertSee(__('match/removePlayer.removed',[],$this->admin->language));
		});


		$this->assertFalse($this->player->inMatch($this->match));
	}
}

