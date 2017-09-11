<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Errors\JsError::class, function (Faker $faker) {
	return [
		'class' => $faker->sentence,
		'user_agent' =>$faker->userAgent,
		'exception' => json_encode([]),
		'vm' => json_encode([]),
	];
});
