<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Errors\Error::class, function (Faker $faker) {
	return [
		'page' => $faker->url,
		'user_id' => null,
	];
});
