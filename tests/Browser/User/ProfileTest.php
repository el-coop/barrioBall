<?php

namespace Tests\Browser\User;

use App\Models\Admin;
use App\Models\Match;
use App\Models\User;
use Tests\Browser\Pages\User\ProfilePage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileTest extends DuskTestCase {
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
	 * @group profile
	 */
	public function test_shows_user_played_matches(): void {
		$this->browse(function (Browser $browser) {
			$matches = factory(Match::class, 5)->create()->each(function ($match) {
				$match->addPlayer($this->user);
			});

			$browser->loginAs($this->user)
				->visit(new ProfilePage)
				->waitFor('@tables-loaded')
				->assertSeeMatches($matches);

		});
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_shows_user_managed_matches(): void {
		$this->browse(function (Browser $browser) {
			$matches = factory(Match::class, 5)->create()->each(function ($match) {
				$match->addManager($this->user);
			});

			$browser->loginAs($this->user)
				->visit(new ProfilePage)
				->waitFor('@tables-loaded')
				->assertSeeMatches($matches);
		});
	}


	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_delete_user(): void {
		$this->browse(function (Browser $browser) {

			$browser->loginAs($this->user)
				->visit(new ProfilePage)
				->click('@delete-button')
				->acceptDelete($this->user)
				->assertPathIs(action('Auth\LoginController@showLoginForm',[],false));

			$this->assertFalse($this->user->exists());
		});
	}
}
