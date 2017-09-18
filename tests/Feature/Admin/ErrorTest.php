<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Errors\Error;
use App\Models\Errors\JsError;
use App\Models\Errors\PhpError;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorTest extends TestCase
{
	use RefreshDatabase;

	protected $jserror;

	public function setUp() {
		parent::setUp();
		factory(JsError::class)->create()->each(function($error){
			$error->error()->save(factory(Error::class)->make());
		});
		factory(Admin::class)->create()->each(function($user){
			$user->user()->save(factory(User::class)->make());
		});


	}

	public function test_shows_errors_page()
	{
		$response = $this->actingAs(User::first())->get(action('Admin\ErrorController@show'));

		$response->assertStatus(200);
		$response->assertSee("<title>Errors Table");
	}


	public function test_logs_js_errors()
	{

		$this->post(action('ErrorController@store'),[
			'page' => "/",
			'message' => "message",
			'source' => "source",
			'lineNo' => "1",
			'trace' => "[]",
			'userAgent' => 'firefox',
			'vm' => "vm"
		],['HTTP_X-Requested-With' => 'XMLHttpRequest']);

		$this->assertDatabaseHas('errors',[
			'page' => '/',
			'errorable_type' => 'JSError',
			'errorable_id' => JsError::first()->id + 1
		]);
		$this->assertDatabaseHas('js_errors',[
			'class' => 'message',
			'user_agent' => 'firefox',
			'vm' => 'vm'
		]);
	}

	public function test_cant_logs_js_errors_without_ajax()
	{
		$this->post(action('ErrorController@store'),[
			'page' => "/",
			'message' => "message",
			'source' => "source",
			'lineNo' => "1",
			'trace' => "[]",
			'userAgent' => 'firefox',
			'vm' => 'vm'
		]);

		$this->assertDatabaseMissing('errors',[
			'page' => '/',
			'errorable_type' => 'JSError',
			'errorable_id' =>  JsError::first()->id + 1
		]);

		$this->assertDatabaseMissing('js_errors',[
			'class' => 'message',
			'user_agent' => 'firefox',
			'vm' => '"vm"'
		]);
	}



	public function test_resolves_error()
	{
		factory(PhpError::class)->create()->each(function($error){
			$error->error()->save(factory(Error::class)->make());
		});

		$error = PhpError::first()->error;

		$this->actingAs(User::first())->delete(action('Admin\ErrorController@delete', $error));
		$this->assertDatabaseMissing('errors',['id' => $error->id]);
		$this->assertDatabaseMissing('php_errors',['id' => $error->errorable_id]);
	}


}
