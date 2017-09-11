<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Errors\PhpError::class, function (Faker $faker) {
	return [
		'message' => $faker->sentence,
		'request' => json_encode([]),
		'exception' => json_encode([])
	];
});
