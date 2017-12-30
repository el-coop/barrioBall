<?php

namespace Tests\Browser\Admin;

use App\Models\Admin;
use App\Models\User;
use Tests\Browser\Pages\Admin\OverviewPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddAdminTest extends DuskTestCase {

	use DatabaseMigrations;

	protected $admin;
	protected $user;

	public function setUp() {
		parent::setUp();
		$this->admin = factory(User::class)->create([
			'user_id' => function () {
				return factory(Admin::class)->create()->id;
			},
			'user_type' => 'Admin',
		]);

		$this->user = factory(User::class)->create();
	}

	/**
	 * @test
	 * @admin
	 * @makeAdmin
	 */
	public function test_can_make_admin(): void {
		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->admin)->visit(new OverviewPage)
				->waitFor('@make-admin-button')
				->click('@make-admin-button')
				->assertSee(__('global.success',[],$this->admin->language))
				->waitForText($this->user->email)
				->assertMissing('@make-admin-button');
		});
	}
}
