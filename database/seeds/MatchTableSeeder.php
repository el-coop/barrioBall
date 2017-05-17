<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class MatchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$user = User::first();
    	factory(App\Models\Match::class,50)->create()->each(function($match) use ($user){
			$match->addManager($user);
		});
    }
}
