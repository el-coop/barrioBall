<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Errors\Error;
use App\Models\Errors\JsError;
use App\Models\Errors\PhpError;
use App\Models\Match;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OverviewTest extends TestCase {
	use RefreshDatabase;

	protected $admin;

	public function setUp() {
		parent::setUp();
		$this->admin = factory(User::class)->create([
			'user_type' => 'Admin',
			'user_id' => function () {
				return factory(Admin::class)->create()->id;
			},
		]);
	}

	public function test_guest_cant_see_overview_page(): void {

		$this->get(action('Admin\PageController@index'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}


	public function test_user_cant_see_overview_page(): void {
		$user = factory(User::class)->create();

		$this->actingAs($user)->get(action('Admin\PageController@index'))
			->assertRedirect(action('HomeController@index'));
	}


	public function test_admin_sees_overview_page(): void {
		factory(User::class, 3)->create([
			'created_at' => Carbon::now(),
		]);
		factory(User::class, 3)->create([
			'created_at' => Carbon::now()->subDays(2),
		]);
		factory(Match::class, 3)->create([
			'created_at' => Carbon::now(),
		]);
		factory(Match::class, 3)->create([
			'created_at' => Carbon::now()->subDays(2),
		]);

		$this->actingAs($this->admin)
			->get(action('Admin\PageController@index'))
			->assertSee('<title>' . __('navbar.adminOverview'))
			->assertSee(__('admin/overview.usersCount', ['users' => 7]))
			->assertSee(__('admin/overview.newUsersCount', ['users' => 4]))
			->assertSee(__('admin/overview.newMatchesCount', ['matches' => 3]))
			->assertSee(__('admin/overview.matchesCount', ['matches' => 6]));
	}

	public function test_admin_doesnt_see_errors_if_none(): void {
		$this->actingAs($this->admin)
			->get(action('Admin\PageController@index'))
			->assertDontSee('div class="alert alert-danger">');
	}

	public function test_admin_sees_errors_if_exit(): void {
		factory(Error::class, 3)->create([
			'errorable_id' => function () {
				return factory(PhpError::class)->create([
					'created_at' => Carbon::now(),
				])->id;
			},
			'errorable_type' => 'PHPError',
			'created_at' => Carbon::now(),
		]);

		factory(Error::class, 3)->create([
			'errorable_id' => function () {
				return factory(JsError::class)->create([
					'created_at' => Carbon::now()->subDays(2),
				])->id;
			},
			'errorable_type' => 'JSError',
			'created_at' => Carbon::now()->subDays(2),
		]);


		$this->actingAs($this->admin)
			->get(action('Admin\PageController@index'))
			->assertSee(__('admin/overview.errorsCount', [
				'errors' => 6,
				'url' => action('Admin\ErrorController@show'),
			]))->assertSee(__('admin/overview.newErrorsCount', [
				'errors' => 3,
			]))->assertSee(__('admin/overview.phpErrorsCount', [
				'errors' => 3,
			]))->assertSee(__('admin/overview.jsErrorsCount', [
				'errors' => 3,
			]));
	}
}
