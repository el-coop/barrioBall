<?php

namespace Tests\Browser\Match;

use App\Models\Match;
use App\Models\User;
use Tests\Browser\Pages\Match\ShowPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteTest extends DuskTestCase
{
	use DatabaseMigrations;

	protected $match;
	protected $manager;

	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create();
		$this->manager = factory(User::class)->create();
		$this->match->addManager($this->manager);
	}

	/**
	 * @test
	 * @group deleteMatch
	 * @group match
	 * @group showMatch
	 */
	public function test_manager_can_delete_match(): void {

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->manager)
				->visit(new ShowPage($this->match))
				->click('@delete-button')
				->click('.swal2-confirm')
				->assertPathIs(action('HomeController@index',[],false));
		});

		$this->assertFalse($this->match->exists());
	}
}
