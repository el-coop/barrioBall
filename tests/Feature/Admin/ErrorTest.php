<?php

namespace Tests\Feature\Admin;

use App\Models\Errors\Error;
use App\Models\Errors\PhpError;
use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ErrorTest extends TestCase
{

	use DatabaseMigrations;

	public function test_shows_errors_page()
	{
		$response = $this->get(action('Admin\ErrorController@show'));

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
			'vm' => 'vm'
		],['HTTP_X-Requested-With' => 'XMLHttpRequest']);

		$this->assertDatabaseHas('errors',[
			'page' => '/',
			'errorable_type' => 'JSError',
			'errorable_id' => '1'
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
			'errorable_id' => '1'
		]);

		$this->assertDatabaseMissing('js_errors',[
			'class' => 'message',
			'user_agent' => 'firefox',
			'vm' => 'vm'
		]);
	}

	public function test_resolves_error()
	{
		$error = factory(PhpError::class)->create()->each(function($error){
			$error->error()->save(factory(Error::class)->make());
		});

		$this->delete( action('Admin\ErrorController@delete', $error));
		$this->assertDatabaseMissing('errors',['id' => 1]);
		$this->assertDatabaseMissing('php_errors',['id' => 1]);
	}


}
