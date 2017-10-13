<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Tests\TestCase;

class AdminTest extends TestCase
{
	protected $admin;

	public function setUp() {
		parent::setUp();

		$this->admin = factory(User::class)->create([
			'user_id' => function(){
				return factory(Admin::class)->create()->id;
			},
			'user_type' => 'Admin'
		])->user;
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_user_relationship(): void {

		$this->assertInstanceOf(MorphOne::class, $this->admin->user());
		$this->assertInstanceOf(User::class, $this->admin->user);
	}
}
