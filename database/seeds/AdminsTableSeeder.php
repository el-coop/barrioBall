<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Admin::class,1)->create()->each(function($user){
        	$user->user()->save(factory(App\Models\User::class)->make([
        		'username' => 'admin',
        		'email' => 'admin@barrioball.dev',
				'language' => 'en',
				'password' => bcrypt('123456')
			]));
		});
    }
}
