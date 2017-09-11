<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Match::class, function (Faker $faker) {
	return [
		'name' => $faker->unique()->firstName . "'s Match",
		'address' => $faker->address,
		'lat' => $faker->randomFloat(15, -34.6376, -34.5728),
		'lng' => $faker->randomFloat(15, -58.4544, -58.3222),
		'public' => $faker->boolean(),
		'date' => $faker->dateTimeBetween('now', '+1 week'),
		'time' => $faker->time(),
		'players' => $faker->numberBetween(4, 7) * 2,
		'description' => $faker->paragraph()
	];
});
