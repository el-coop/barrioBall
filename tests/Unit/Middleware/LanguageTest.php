<?php

namespace Tests\Unit\Middleware;

use App;
use App\Http\Middleware\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\Middleware\StartSession;
use Tests\TestCase;

class LanguageTest extends TestCase {
	protected $request;
	protected $middleware;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->request = (new Request());
		(new StartSession(app('session')))->handle($this->request, function () {
			return new Response();
		});
		$this->middleware = new Language();

	}

	/**
	 * @test
	 * @group middleware
	 * @group language
	 */
	public function test_it_sets_language_for_spanish_user(): void {
		$this->request->setUserResolver(function () {
			return factory(User::class)->create([
				'language' => 'es',
			]);
		});
		$this->middleware->handle($this->request, function () {
		});
		$this->assertEquals('es', App::getLocale());
	}

	/**
	 * @test
	 * @group middleware
	 * @group language
	 */
	public function test_it_sets_language_for_english_user(): void {
		$this->request->setUserResolver(function () {
			return factory(User::class)->create([
				'language' => 'en',
			]);
		});
		$this->middleware->handle($this->request, function () {
		});
		$this->assertEquals('en', App::getLocale());
	}

	/**
	 * @test
	 * @group middleware
	 * @group language
	 */
	public function test_it_sets_language_for_english_guest(): void {
		$this->request->session()->put('appLocale','en');
		$this->middleware->handle($this->request, function () {
		});
		$this->assertEquals('en', App::getLocale());
	}

	/**
	 * @test
	 * @group middleware
	 * @group language
	 */
	public function test_it_sets_language_for_spanish_guest(): void {
		$this->request->session()->put('appLocale','es');
		$this->middleware->handle($this->request, function () {
		});
		$this->assertEquals('es', App::getLocale());
	}

	/**
	 * @test
	 * @group middleware
	 * @group language
	 */
	public function test_it_defaults_to_english() {
		$this->middleware->handle($this->request, function (): void {
		});
		$this->assertEquals('en', App::getLocale());
	}
}
