<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Tests\TestCase;

class PlayerTest extends TestCase
{
	protected $player;

	public function setUp() {
		parent::setUp();

		$this->player = factory(User::class)->create()->user;
	}

	/**
	 * @test
	 * @group user
	 */
	public function test_user_relationship(): void {

		$this->assertInstanceOf(MorphOne::class, $this->player->user());
		$this->assertInstanceOf(User::class, $this->player->user);
	}
}
