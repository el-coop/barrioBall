<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddAdminsTest extends TestCase
{
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

	/**
	 * @test
	 * @group admin
	 * @group addAdmin
	 */
	public function test_admin_can_convert_users_to_admin(): void {
		$user = factory(User::class)->create();

		$this->actingAs($this->admin)->post(action('Admin\UserController@addAdmin',$user))
			->assertSessionHas('alert',__('global.success',[],$this->admin->language));

		$this->assertTrue($user->fresh()->isAdmin());
	}

	/**
	 * @test
	 * @group admin
	 * @group addAdmin
	 */
	public function test_admin_can_convert_admin_twice(): void {

		$this->actingAs($this->admin)->post(action('Admin\UserController@addAdmin',$this->admin))
			->assertSessionHasErrors('alreadyAdmin',__('global.error',[],$this->admin->language));

	}

	/**
	 * @test
	 * @group admin
	 * @group addAdmin
	 */
	public function test_guest_cant_convert_users_to_admin(): void {
		$user = factory(User::class)->create();

		$this->post(action('Admin\UserController@addAdmin',$user))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));

		$this->assertFalse($user->fresh()->isAdmin());
	}

	/**
	 * @test
	 * @group admin
	 * @group addAdmin
	 */
	public function test_user_cant_convert_users_to_admin(): void {
		$user = factory(User::class)->create();

		$this->actingAs($user)->post(action('Admin\UserController@addAdmin',$user))
			->assertRedirect(action('HomeController@index'));

		$this->assertFalse($user->fresh()->isAdmin());
	}

}
