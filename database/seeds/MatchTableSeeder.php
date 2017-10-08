<?php

use App\Models\Admin;
use App\Models\Match;
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
    	$user = Admin::first()->user;
    	factory(Match::class,50)->create()->each(function($match) use ($user){
			$match->addManager($user);
		});
    }
}
