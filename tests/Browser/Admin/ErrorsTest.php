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

class ErrorsTest extends DuskTestCase
{
	use DatabaseMigrations;

	protected function makeAdmin(){
		factory(Admin::class)->create()->each(function($user){
			$user->user()->save(factory(User::class)->make());
		});

	}

	public function setUp() {
		parent::setUp();
		$this->makeAdmin();
	}

    public function test_shows_php_errors()
    {
        $this->browse(function (Browser $browser) {
			$errors = factory(PhpError::class,5)->create()->each(function($error){
				$error->error()->save(factory(Error::class)->make());
			});

            $browser->loginAs(User::first())->visit(action('Admin\ErrorController@show'))
                    ->waitFor('.vuetable-slot');

            foreach ($errors as $error){
				$browser->assertSee($error->error->page);
				$browser->assertSee($error->message);
			}
        });
    }

	public function test_shows_js_errors()
	{
		$this->browse(function (Browser $browser) {
			$errors = factory(JsError::class,5)->create()->each(function($error){
				$error->error()->save(factory(Error::class)->make());
			});

			$browser->loginAs(User::first())->visit(action('Admin\ErrorController@show'))
				->waitFor('.vuetable-slot');

			foreach ($errors as $error){
				$browser->assertSee($error->error->page);
				$browser->assertSee($error->class);
			}
		});
	}

	public function test_button_resolves_errors()
	{
		$this->browse(function (Browser $browser) {
			factory(PhpError::class)->create()->each(function($error){
				$error->error()->save(factory(Error::class)->make());
			});

			$browser->loginAs(User::first())->visit(action('Admin\ErrorController@show'))
				->waitFor('.vuetable-slot');

			$browser->click('.btn.btn-success')
					->waitUntilMissing('.btn.btn-success');

			$this->assertDatabaseMissing('errors',['id' => 1]);
			$this->assertDatabaseMissing('php_errors',['id' => 1]);
		});
	}
}
