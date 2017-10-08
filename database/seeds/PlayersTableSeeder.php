<?php

use App\Models\Player;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlayersTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {

		factory(User::class)->create([
			'username' => 'player 1',
			'email' => 'user@barrioball.dev',
			'language' => 'en',
			'password' => bcrypt('123456'),
		]);

		factory(User::class, 30)->create();

	}
}
