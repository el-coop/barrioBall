<?php

use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
	static $password;

	return [
		'username' => $faker->unique()->userName,
		'email' => $faker->unique()->safeEmail,
		'password' => $password ?: $password = bcrypt('secret'),
		'remember_token' => str_random(10),
		'language' => $faker->randomElement(['en','es'])
	];
});
