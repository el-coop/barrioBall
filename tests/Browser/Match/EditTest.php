<?php

namespace Tests\Browser\Match;

use App\Models\Match;
use App\Models\User;
use Carbon\Carbon;
use Tests\Browser\Pages\Match\EditPage;
use Tests\Browser\Pages\Match\ShowPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditTest extends DuskTestCase {
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
	 * @group editMatch
	 * @group match
	 * @group showMatch
	 */
	public function test_manager_can_see_edit_page(): void {

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->manager)
				->visit(new ShowPage($this->match))
				->click('@edit-button')
				->assertPathIs(action('Match\MatchController@showEditForm',$this->match,false));
		});
	}


	/**
	 * @test
	 * @group editMatch
	 * @group match
	 */
	public function test_manager_can_edit_match(): void {

		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->manager)
				->visit(new EditPage($this->match))
				->type('name','Nurs Match')
				->type('address','test 10')
				->type('date', Carbon::now()->addDay()->format('d/m/y'))
				->type('time','20:00')
				->select('players', 10)
				->type('description','Nurs desc')
				->click('@submit')
				->assertPathIs(action('Match\MatchController@showMatch',$this->match,false));
		});

		$match = $this->match->fresh();
		$this->assertEquals('Nurs Match',$match->name);
		$this->assertEquals('test 10',$match->address);
		$this->assertEquals(Carbon::now()->addDay()->format('d/m/y'),$match->date);
		$this->assertEquals('20:00',$match->time);
		$this->assertEquals(10,$match->players);
		$this->assertEquals('Nurs desc',$match->description);
	}

}
