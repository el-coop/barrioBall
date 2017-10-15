<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Errors\Error;
use App\Models\Errors\JsError;
use App\Models\Errors\PhpError;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorTest extends TestCase {
	use RefreshDatabase;

	protected $jsError;
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

	/**
	 * @test
	 * @group admin
	 * @group error
	 */
	public function test_shows_errors_page(): void {
		$this->actingAs($this->admin)->get(action('Admin\ErrorController@show'))
			->assertStatus(200)
			->assertSee('<title>' . __('navbar.errorsLink', [], $this->admin->language));
	}

	/**
	 * @test
	 * @group admin
	 * @group error
	 */
	public function test_doesnt_show_errors_page_not_loged_in(): void {
		$this->get(action('Admin\ErrorController@show'))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	/**
	 * @test
	 * @group admin
	 * @group error
	 */
	public function test_doesnt_show_errors_page_not_admin(): void {
		$this->actingAs(factory(User::class)->create())
			->get(action('Admin\ErrorController@show'))
			->assertRedirect(action('HomeController@index'));
	}

	/**
	 * @test
	 * @group global
	 * @group error
	 */
	public function test_logs_js_errors(): void {

		$this->post(action('ErrorController@store'), [
			'page' => "/",
			'message' => "message",
			'source' => "source",
			'lineNo' => "1",
			'trace' => "[]",
			'userAgent' => 'firefox',
			'vm' => "vm",
		], ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
			->assertJson([
				'status' => 'Success',
			]);

		$error = Error::first();
		$this->assertArraySubset([
			'page' => "/",
			'errorable_type' => 'JSError',
		], $error->toArray());
		$this->assertArraySubset([
			'class' => 'message',
			'user_agent' => 'firefox',
			'vm' => 'vm',
		], $error->errorable->toArray());
	}

	/**
	 * @test
	 * @group global
	 * @group error
	 */
	public function test_cant_logs_js_errors_without_ajax(): void {
		$this->post(action('ErrorController@store'), [
			'page' => "/",
			'message' => "message",
			'source' => "source",
			'lineNo' => "1",
			'trace' => "[]",
			'userAgent' => 'firefox',
			'vm' => 'vm',
		]);

		$this->assertEquals(0, Error::count());
		$this->assertEquals(0, JsError::count());
	}


	/**
	 * @test
	 * @group admin
	 * @group error
	 */
	public function test_resolves_error(): void {
		$error = factory(Error::class)->create([
			'errorable_id' => function () {
				return factory(PhpError::class)->create()->id;
			},
			'errorable_type' => 'PHPError',
		]);

		$this->actingAs($this->admin)->delete(action('Admin\ErrorController@delete', $error))
			->assertJson([
				'status' => 'Success',
			]);

		$this->assertEquals(0,Error::count());
		$this->assertEquals(0,PhpError::count());
	}

	/**
	 * @test
	 * @group admin
	 * @group error
	 */
	public function test_not_logged_in_doesnt_resolve_error(): void {
		$error = factory(Error::class)->create([
			'errorable_id' => function () {
				return factory(PhpError::class)->create()->id;
			},
			'errorable_type' => 'PHPError',
		]);

		$this->delete(action('Admin\ErrorController@delete', $error))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));

		$this->assertNotEquals(0,Error::count());
		$this->assertNotEquals(0,PhpError::count());
	}

	/**
	 * @test
	 * @group admin
	 * @group error
	 */
	public function test_non_admin_doesnt_resolve_error(): void {
		$error = factory(Error::class)->create([
			'errorable_id' => function () {
				return factory(PhpError::class)->create()->id;
			},
			'errorable_type' => 'PHPError',
		]);

		$this->actingAs(factory(User::class)->create())
			->delete(action('Admin\ErrorController@delete', $error))
			->assertRedirect(action('HomeController@index'));

		$this->assertNotEquals(0,Error::count());
		$this->assertNotEquals(0,PhpError::count());
	}
}
