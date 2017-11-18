<?php

namespace Tests\Browser\User;

use App\Models\Match;
use App\Models\User;
use Hash;
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
		$matches = factory(Match::class, 5)->create()->each(function ($match) {
			$match->addPlayer($this->user);
		});

		$this->browse(function (Browser $browser) use ($matches) {

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
		$matches = factory(Match::class, 5)->create()->each(function ($match) {
			$match->addManager($this->user);
		});

		$this->browse(function (Browser $browser) use ($matches) {

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
				->assertPathIs(action('Auth\LoginController@showLoginForm', [], false));
		});
		$this->assertFalse($this->user->exists());
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_change_language(): void {

		$this->user->language = 'es';
		$this->user->save();

		$this->browse(function (Browser $browser) {

			$browser->loginAs($this->user)
				->visit(new ProfilePage)
				->assertSee(__('profile/page.changeLanguage', [], 'es'))
				->select('language', 'en')
				->press('Actualizar idioma')
				->assertSee(__('profile/page.updatedLanguage', [], 'en'));
		});

		$this->assertEquals('en', User::first()->language);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_change_email(): void {

		$this->browse(function (Browser $browser) {

			$browser->loginAs($this->user)
				->visit(new ProfilePage)
				->assertSee(__('profile/page.changeLanguage', [], $this->user->language))
				->type('email', 'test@best.com')
				->press(__('profile/page.updateEmail', [], $this->user->language))
				->assertSee(__('profile/page.updatedEmail', [], $this->user->language));
		});

		$this->assertEquals('test@best.com', User::first()->email);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_change_username(): void {

		$this->browse(function (Browser $browser) {

			$browser->loginAs($this->user)
				->visit(new ProfilePage)
				->type('username', 'newName')
				->press(__('profile/page.updateUsername', [], $this->user->language))
				->assertSee(__('profile/page.updatedUsername', [], $this->user->language));
		});

		$this->assertEquals('newName', User::first()->username);
	}

	/**
	 * @test
	 * @group user
	 * @group profile
	 */
	public function test_user_can_change_password(): void {

		$this->browse(function (Browser $browser) {

			$browser->loginAs($this->user)
				->visit(new ProfilePage)
				->type('password', 'newPass')
				->type('password_confirmation', 'newPass')
				->press(__('profile/page.updatePassword', [], $this->user->language))
				->assertSee(__('profile/page.updatedPassword', [], $this->user->language));
		});

		$this->assertTrue(Hash::check('newPass', User::first()->password));
	}
}
