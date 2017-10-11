<?php

namespace Tests\Browser\Admin;

use App\Models\Admin;
use App\Models\Errors\Error;
use App\Models\Errors\JsError;
use App\Models\Errors\PhpError;
use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ErrorsTest extends DuskTestCase {
	use DatabaseMigrations;

	protected $admin;

	public function setUp() {
		parent::setUp();
		$this->admin = factory(User::class)->create([
			'user_id' => function () {
				return factory(Admin::class)->create()->id;
			},
			'user_type' => 'Admin',
		]);
	}

	public function test_shows_php_errors() {
		$this->browse(function (Browser $browser) {
			$errors = factory(Error::class, 5)->create();

			$browser->loginAs($this->admin)->visit(action('Admin\ErrorController@show'))
				->waitFor('.vuetable-slot');
			foreach ($errors as $error) {
				$browser->assertSee($error->page);
				$browser->assertSee($error->errorable->message);
			}
		});
	}

	public function test_shows_js_errors() {
		$this->browse(function (Browser $browser) {
			$errors = factory(Error::class, 5)->create([
				'errorable_id' => function () {
					return factory(JsError::class);
				},
				'errorable_type' => 'JSError',
			]);

			$browser->loginAs(User::first())->visit(action('Admin\ErrorController@show'))
				->waitFor('.vuetable-slot');

			foreach ($errors as $error) {
				$browser->assertSee($error->page);
				$browser->assertSee($error->errorable->class);
			}
		});
	}

	public function test_button_resolves_errors() {
		$this->browse(function (Browser $browser) {
			factory(Error::class)->create();

			$browser->loginAs(User::first())->visit(action('Admin\ErrorController@show'))
				->waitFor('.vuetable-slot');

			$browser->click('.btn.btn-success')
				->waitUntilMissing('.btn.btn-success');

			$this->assertDatabaseMissing('errors', ['id' => 1]);
			$this->assertDatabaseMissing('php_errors', ['id' => 1]);
		});
	}

	protected function makeAdmin() {
		factory(Admin::class)->create()->each(function ($user) {
			$user->user()->save(factory(User::class)->make());
		});

	}
}
