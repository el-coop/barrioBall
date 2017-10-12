<?php

use App\Models\Player;
use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
	static $password;

	return [
		'username' => $faker->unique()->userName,
		'email' => $faker->unique()->safeEmail,
		'password' => $password ?: $password = bcrypt('secret'),
		'remember_token' => str_random(10),
		'language' => $faker->randomElement(['en', 'es']),
		'user_id' => function () {
			return factory(Player::class)->create()->id;
		},
		'user_type' => 'Player',
	];
});
