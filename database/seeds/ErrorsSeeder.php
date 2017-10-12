<?php

use App\Models\Errors\Error;
use App\Models\Errors\JsError;
use Illuminate\Database\Seeder;

class ErrorsSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		factory(Error::class, 15)->create();
		factory(Error::class, 15)->create([
			'errorable_id' => function () {
				return factory(JsError::class);
			},
			'errorable_type' => 'JSError',
		]);
	}
}
