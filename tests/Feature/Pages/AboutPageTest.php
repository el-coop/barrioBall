<?php

namespace Tests\Feature\Pages;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AboutPageTest extends TestCase
{
	/**
	 * @test
	 * @group global
	 */
	public function test_about_page_is_accessible(): void {
		$this->get(action('HomeController@about'))
			->assertStatus(200)
			->assertSee('<title>' . __('global/welcome.about'));
	}
}
