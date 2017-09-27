<?php

namespace Tests\Browser\Match;

use App\Models\Admin;
use App\Models\Match;
use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateTest extends DuskTestCase {

	use DatabaseMigrations;
	protected $user;

	public function setUp() {
		parent::setUp();
		$this->user = factory(Admin::class)->create();
		$this->user->user()->save(factory(User::class)->make([
			'language' => 'en'
		]));
	}

	public function test_switches_between_map_and_form_on_small_screen() {
		$this->browse(function (Browser $browser) {
			$browser->resize(320, 640)
				->loginAs($this->user->user)
				->visit(action('Match\MatchController@create'))
				->assertVisible('.flipper:not(.flipped)')
				->click('.btn.btn-primary')
				->assertVisible('.flipper.flipped')
				->click('.btn.btn-primary')
				->assertVisible('.flipper:not(.flipped)');


			$browser->maximize();
		});
	}

	public function test_gets_address_confirmation_dialog(){
		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->user->user)
				->visit(action('Match\MatchController@create'))
				->assertVisible('.fa.fa-info-circle')
				->rightClick('.leaflet-proxy.leaflet-zoom-animated')
				->waitFor('.swal2-container.swal2-shown', null)
				->type('.swal2-input','test')
				->click('.swal2-confirm.swal2-styled')
				->assertInputValue('address','test')
				->assertVisible('.btn.btn-primary.btn-block');
		});
	}

	public function test_shows_error_messages(){
		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->user->user)
				->visit(action('Match\MatchController@create'))
				->assertVisible('.fa.fa-info-circle')
				->rightClick('.leaflet-proxy.leaflet-zoom-animated')
				->waitFor('.swal2-container.swal2-shown', null)
				->type('.swal2-input','m')
				->click('.swal2-confirm.swal2-styled')
				->type('name','m')
				->type('date','')
				->type('time','')
				->type('description','m')
				->click('.btn.btn-primary.btn-block')
				->assertSee('The name must be at least 3 characters.')
				->assertSee('The address must be at least 3 characters.')
				->assertSee('The date field is required.')
				->assertSee('The time field is required.')
				->assertSee('The description must be at least 3 characters.');
		});
	}

	public function test_creates_match(){
		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->user->user)
				->visit(action('Match\MatchController@create'))
				->assertVisible('.fa.fa-info-circle')
				->rightClick('.leaflet-proxy.leaflet-zoom-animated')
				->waitFor('.swal2-container.swal2-shown', null)
				->type('.swal2-input','test')
				->click('.swal2-confirm.swal2-styled')
				->type('name','test')
				->type('date','30/09/17')
				->type('time','20:00')
				->type('description','test')
				->click('.btn.btn-primary.btn-block')
				->assertPathIs('/matches/1');
		});

		$this->assertDatabaseHas('matches',[
			'name' => 'test',
			'address' => 'test'
		]);
	}
}
