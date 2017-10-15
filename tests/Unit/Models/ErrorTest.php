<?php

namespace Tests\Unit\Models;

use App\Models\Errors\Error;
use App\Models\Errors\PhpError;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Tests\TestCase;

class ErrorTest extends TestCase
{

	public function setUp() {
		parent::setUp();

		$this->error = factory(Error::class)->create([
			'user_id' => function(){
				return factory(User::class)->create()->id;
			}
		]);
	}

	/**
	 * @test
	 * @group error
	 */
	public function test_errorable_relationship(): void {

		$this->assertInstanceOf(MorphTo::class, $this->error->errorable());
		$this->assertInstanceOf(PhpError::class, $this->error->errorable);
	}

	/**
	 * @test
	 * @group error
	 */
	public function test_user_relationship(): void {

		$this->assertInstanceOf(BelongsTo::class, $this->error->user());
		$this->assertInstanceOf(User::class, $this->error->user);
	}

}
