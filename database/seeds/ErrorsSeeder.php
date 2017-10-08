<?php

use App\Models\Errors\Error;
use Illuminate\Database\Seeder;

class ErrorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	factory(Error::class,30)->create();
	}
}
