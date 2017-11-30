<?php

namespace Tests\Feature\Pages;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TechPageTest extends TestCase {

	/**
	 * @test
	 * @group global
	 */
	public function test_tech_page_is_accessible(): void {
		$this->get(action('HomeController@tech'))
			->assertStatus(200)
			->assertSee(__('global/tech.title'));
	}
}
