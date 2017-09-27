<?php

namespace Tests\Feature\Match;

use App\Models\Admin;
use App\Models\Match;
use App\Models\Player;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTest extends TestCase
{
	use RefreshDatabase;

	protected $user;

	public function setUp() {
		parent::setUp();
		$this->user = factory(Admin::class)->create();
		$this->user->user()->save(factory(User::class)->make([
			'language' => 'en'
		]));
	}


	public function test_cant_not_logged_in_cant_see_create_page()
    {
		$response = $this->get(action('Match\MatchController@showCreate'));
		$response->assertStatus(302);
		$response->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}

	public function test_can_see_page_loged_in(){
		$response = $this->actingAs($this->user->user)->get(action('Match\MatchController@showCreate'));
		$response->assertStatus(200);
		$response->assertSee('<title>Create Match');
	}

	public function test_not_logged_in_cant_create_match(){
		$response = $this->post(action('Match\MatchController@create'));
		$response->assertStatus(302);
		$response->assertRedirect(action('Auth\LoginController@showLoginForm'));
	}


	public function test_can_create_match_and_is_set_as_manager(){
		$response = $this->actingAs($this->user->user)->post(action('Match\MatchController@create'),[
			'name' => 'Nurs Match',
			'address' => 'Test Test',
			'lat' => 0,
			'lng' => 0,
			'players' => 8,
			'date' => Carbon::now()->addDays(1)->format('d/m/y'),
			'time' => Carbon::now()->format('H:i'),
			'description' => 'description'
		]);
		$response->assertStatus(302);
		$response->assertRedirect(action('Match\MatchController@showMatch', Match::first()));
		$this->assertDatabaseHas('matches',[
			'name' => 'Nurs Match',
			'address' => 'Test Test'
		]);
	}

	public function test_validates_input(){
		$response = $this->actingAs($this->user->user)->post(action('Match\MatchController@create'),[
			'name' => 'N',
			'address' => 'T',
			'description' => 'd'
		]);
		$response->assertStatus(302);
		$response->assertSessionHasErrors([
			'name', 'address', 'lat', 'lng', 'players', 'date','time','description'
		]);

	}
}
