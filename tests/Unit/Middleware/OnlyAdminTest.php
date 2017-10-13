<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\Users\OnlyAdmin;
use App\Models\Admin;
use App\Models\User;
use Request;
use Tests\TestCase;

class OnlyAdminTest extends TestCase {

	protected $request;
	protected $middleware;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->request = Request::create('/admin/errors', 'GET');
		$this->middleware = new OnlyAdmin();

	}
	/**
	 * @test
	 * @group middleware
	 * @group auth
	 */
	public function test_it_doesnt_allow_guests_in(): void {
		$response = $this->middleware->handle($this->request, function () {});
		$this->assertEquals(302, $response->getStatusCode());
		$this->assertEquals(action('HomeController@index'),$response->headers->get('location'));
	}

	/**
	 * @test
	 * @group middleware
	 * @group auth
	 */
	public function test_it_doesnt_allow_nonAdmins_in(): void {
		$this->request->setUserResolver(function () {
			return factory(User::class)->create();
		});
		$response = $this->middleware->handle($this->request, function () {});
		$this->assertEquals(302, $response->getStatusCode());
		$this->assertEquals(action('HomeController@index'),$response->headers->get('location'));
	}


	/**
	 * @test
	 * @group middleware
	 * @group auth
	 */
	public function test_it__allows_admins_in(): void {
		$this->request->setUserResolver(function () {
			return factory(User::class)->create([
				'user_id' => function () {
					return factory(Admin::class)->create()->id;
				},
				'user_type' => 'Admin',
			]);
		});
		$response = $this->middleware->handle($this->request, function () {
			return true;
		});
		$this->assertTrue($response);
	}
}
