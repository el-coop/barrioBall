<?php

namespace Tests\Feature\User;

use App\Models\Admin;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LanguageTest extends TestCase {

	use DatabaseMigrations;

	public function test_it_shows_english_text_for_english_language_user() {
		factory(Admin::class)->create()->each(function ($user) {
			$user->user()->save(factory(User::class)->make([
				'language' => 'en'
			]));
		});

		$this->actingAs(User::first())
			->get(action('Match\MatchController@showSearch'))
			->assertSee('Search Match');
	}


	public function test_it_shows_spanish_text_for_spanish_language() {
		factory(Admin::class)->create()->each(function ($user) {
			$user->user()->save(factory(User::class)->make([
				'language' => 'es'
			]));
		});

		$this->actingAs(User::first())
			->get(action('Match\MatchController@showSearch'))
			->assertSee('Crear Partido');
	}
}
