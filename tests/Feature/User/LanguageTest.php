<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageTest extends TestCase {

	use RefreshDatabase;

	/**
	 * @test
	 * @group user
	 * @group language
	 */
	public function test_it_shows_english_text_for_english_language_user(): void {
		$user = factory(User::class)->create([
			'language' => 'en'
		]);

		$this->actingAs($user)
			->get(action('Match\MatchController@showSearch'))
			->assertSee(__('navbar.createLink',[],'en'));
	}

	/**
	 * @test
	 * @group user
	 * @group language
	 */
	public function test_it_shows_spanish_text_for_spanish_language(): void {
		$user = factory(User::class)->create([
			'language' => 'es'
		]);

		$this->actingAs($user)
			->get(action('Match\MatchController@showSearch'))
			->assertSee(__('navbar.createLink',[],'es'));
	}
}
