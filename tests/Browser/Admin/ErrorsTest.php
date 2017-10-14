<?php

namespace Tests\Browser\Admin;

use App\Models\Admin;
use App\Models\Errors\Error;
use App\Models\Errors\JsError;
use App\Models\Errors\PhpError;
use App\Models\User;
use Tests\Browser\Pages\Admin\ErrorsPage;
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

	/**
	 * @test
	 * @group errors
	 * @group admin
	 */
	public function test_shows_php_errors(): void {
		$this->browse(function (Browser $browser) {
			$errors = factory(Error::class, 5)->create();

			$browser->loginAs($this->admin)->visit(new ErrorsPage)
				->waitFor('@tables-loaded')
				->assertSeePhpErrors($errors);
		});
	}

	/**
	 * @test
	 * @group errors
	 * @group admin
	 */
	public function test_shows_js_errors(): void {
		$this->browse(function (Browser $browser) {
			$errors = factory(Error::class, 5)->create([
				'errorable_id' => function () {
					return factory(JsError::class);
				},
				'errorable_type' => 'JSError',
			]);

			$browser->loginAs($this->admin)->visit(new ErrorsPage)
				->waitFor('@tables-loaded')
				->assertSeeJsErrors($errors);
		});
	}

	/**
	 * @test
	 * @group errors
	 * @group admin
	 */
	public function test_button_resolves_errors(): void {
		$this->browse(function (Browser $browser) {
			$error = factory(Error::class)->create();
			$errorable = $error->errorable;

			$browser->loginAs($this->admin)->visit(new ErrorsPage)
				->waitFor('@tables-loaded')
				->click('@resolve-button')
				->waitUntilMissing('@resolve-button');

			$this->assertFalse($error->exists());
			$this->assertFalse($errorable->exists());
		});
	}
}
