<?php

namespace Tests\Unit\Models;

use App\Models\Errors\Error;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhpErrorTest extends TestCase {

	use RefreshDatabase;

	public function setUp() {
		parent::setUp();

		$this->error = factory(Error::class)->create()->errorable;
	}

	/**
	 * @test
	 * @group error
	 */
	public function test_error_relationship(): void {
		$this->assertInstanceOf(MorphOne::class, $this->error->error());
		$this->assertInstanceOf(Error::class, $this->error->error);
	}


	/**
	 * @test
	 * @group error
	 */
	public function test_delete(): void {
		$this->error->delete();
		$this->assertDatabaseMissing('php_errors', [
			'id' => $this->error->id,
		]);
		$this->assertDatabaseMissing('errors', [
			'id' => $this->error->error->id,
		]);
	}
}
