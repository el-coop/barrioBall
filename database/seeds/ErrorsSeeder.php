<?php

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
		factory(\App\Models\Errors\PhpError::class,15)->create()->each(function($error){
			$error->error()->save(factory(App\Models\Errors\Error::class)->make());
		});

		factory(\App\Models\Errors\JsError::class,15)->create()->each(function($error){
			$error->error()->save(factory(App\Models\Errors\Error::class)->make());
		});

	}
}
