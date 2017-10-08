<?php

use App\Models\Admin;
use App\Models\Player;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		factory(Admin::class, 1)->create()->each(function ($user) {
			$user->user()->save(factory(User::class)->make([
				'username' => 'admin',
				'email' => 'admin@barrioball.dev',
				'language' => 'en',
				'password' => bcrypt('123456'),
			]));
			Player::first()->delete();
		});
	}
}
