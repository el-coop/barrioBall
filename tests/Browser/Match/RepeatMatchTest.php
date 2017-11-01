<?php

namespace Tests\Browser\Match;

use App\Models\Match;
use App\Models\User;
use Carbon\Carbon;
use Tests\Browser\Pages\Match\ShowPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RepeatMatchTest extends DuskTestCase {
	use DatabaseMigrations;

	protected $manager;
	protected $match;

	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create([
			'date_time' => Carbon::now()->subDay(),
		]);
		$this->manager = factory(User::class)->create();
		$this->match->addManager($this->manager);
	}


	/**
	 * @test
	 * @group match
	 * @group showMatch
	 * @group current
	 */
	public function test_repeat_match_works(): void {
		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->manager)->visit(new ShowPage($this->match))
				->click('@repeat-button')
				->type('date',  Carbon::now()->addDay()->format('d/m/y'))
				->type('time',  '22:00');
		});
	}
}
