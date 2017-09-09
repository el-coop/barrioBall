<?php

use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		factory(Player::class,1)->create()->each(function($user){
			$user->user()->save(factory(App\Models\User::class)->make([
				'username' => 'player 1',
				'email' => 'user@barrioball.dev',
				'language' => 'en',
				'password' => bcrypt('123456')
			]));
		});

		factory(Player::class,30)->create()->each(function($user){
			$user->user()->save(factory(App\Models\User::class)->make([
			]));
		});

	}
}
