<?php

namespace Tests\Browser\Match;

use App\Models\Admin;
use App\Models\Match;
use App\Models\User;
use Carbon\Carbon;
use PhpParser\Node\Stmt\Case_;
use Tests\Browser\Pages\Match\CreatePage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateTest extends DuskTestCase {

	use DatabaseMigrations;
	protected $user;

	public function setUp() {
		parent::setUp();
		$this->user = factory(User::class)->create([
			'language' => 'en'
		]);
	}

	/**
	 * @test
	 * @group createMatch
	 * @group match
	 */
	public function test_switches_between_map_and_form_on_small_screen(): void {
		$this->browse(function (Browser $browser) {

			$browser->resize(320, 640)
				->loginAs($this->user)
				->visit(new CreatePage)
				->assertVisible('@front-shown')
				->click('@flip-button')
				->assertVisible('@back-shown')
				->click('@flip-button')
				->assertVisible('@front-shown');

			$browser->maximize();
		});
	}

	/**
	 * @test
	 * @group createMatch
	 * @group match
	 */
	public function test_gets_address_confirmation_dialog(): void {
		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->user)
				->visit(new CreatePage)
				->assertVisible('@info-tooltip')
				->rightClick('@map')
				->fillAddressModal('test')
				->assertInputValue('address', 'test')
				->assertVisible('@submit-button');
		});
	}

	/**
	 * @test
	 * @group createMatch
	 * @group match
	 */
	public function test_shows_error_messages(): void {
		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->user)
				->visit(new CreatePage)
				->assertVisible('@info-tooltip')
				->rightClick('@map')
				->fillAddressModal('m')
				->submitForm([
					'name' => 'm',
					'date' => '',
					'time' => '',
					'description' => 'm',
				])
				->assertSee('The name must be at least 3 characters.')
				->assertSee('The address must be at least 3 characters.')
				->assertSee('The date field is required.')
				->assertSee('The time field is required.')
				->assertSee('The description must be at least 3 characters.');
		});
	}

	/**
	 * @test
	 * @group createMatch
	 * @group match
	 */
	public function test_creates_match(): void {
		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->user)
				->visit(new CreatePage)
				->assertVisible('@info-tooltip')
				->rightClick('@map')
				->fillAddressModal('test')
				->submitForm([
					'name' => 'test',
					'date' => Carbon::now()->addDay()->format('d/n/y'),
					'time' => '20:00',
					'description' => 'test',
				])
				->assertPathIs(action('Match\MatchController@showMatch', Match::first(),false));
		});

		$this->assertDatabaseHas('matches', [
			'name' => 'test',
			'address' => 'test',
		]);
	}
}
