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
		factory(User::class)->create([
			'username' => 'admin',
			'email' => 'admin@barrioball.dev',
			'language' => 'en',
			'password' => bcrypt('123456'),
			'user_id' => function () {
				return factory(Admin::class)->create()->id;
			},
			'user_type' => 'Admin',
		]);
	}
}
