<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$this->call(AdminsTableSeeder::class);
		$this->call(PlayersTableSeeder::class);
		$this->call(MatchTableSeeder::class);
		$this->call(ErrorsSeeder::class);
		$this->call(ConversationsSeeder::class);
    }
}
