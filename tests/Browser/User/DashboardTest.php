<?php

namespace Tests\Browser\User;

use App\Models\Match;
use App\Models\User;
use Cache;
use Tests\Browser\Pages\User\DashboardPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DashboardTest extends DuskTestCase
{
	use DatabaseMigrations;

	protected $user;

	public function setUp() {
		parent::setUp();
		$this->user = factory(User::class)->create([
			'language' => 'es',
		]);
	}

	/**
	 * @test
	 * @group user
	 * @group dashboard
	 */
	public function test_shows_user_played_matches(): void {
		$matches = factory(Match::class, 5)->create()->each(function ($match) {
			$match->addPlayer($this->user);
		});

		/*Cache::shouldReceive('tags')->once()->with("{$this->user->username}_played")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('rememberForever');*/

		$this->browse(function (Browser $browser) use($matches){
			$browser->resize(1200,1200);
			$browser->loginAs($this->user)
				->visit(new DashboardPage)
				->waitFor('@tables-loaded')
				->assertSeeMatches($matches);

		});
	}

	/**
	 * @test
	 * @group user
	 * @group dashboard
	 */
	public function test_shows_user_managed_matches(): void {
		$matches = factory(Match::class, 5)->create()->each(function ($match) {
			$match->addManager($this->user);
		});

		$this->browse(function (Browser $browser) use ($matches) {
			$browser->resize(1200,1200);
			$browser->loginAs($this->user)
				->visit(new DashboardPage)
				->waitFor('@tables-loaded')
				->assertSeeMatches($matches);
		});
	}

}
