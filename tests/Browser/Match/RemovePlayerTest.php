<?php

namespace Tests\Browser\Match;

use App\Models\Admin;
use App\Models\Match;
use App\Models\Player;
use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RemovePlayerTest extends DuskTestCase
{
	use DatabaseMigrations;

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

    public function test_remove_player_process_works()
    {

        $this->browse(function (Browser $browser) {
			$browser->loginAs($this->admin)
				->visit(action('Match\MatchController@showMatch',$this->match))
				->click('.list-group-item > .btn.btn-danger')
				->type('#remove-user .form-control','I hate you')
				->press('#remove-user .btn.btn-danger.btn-block')
				->assertSee('The user was removed from the match');
        });


		$this->assertFalse($this->player->inMatch($this->match));
    }
}

