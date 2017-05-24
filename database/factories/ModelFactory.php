<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
	static $password;

	return [
		'username' => $faker->unique()->userName,
		'email' => $faker->unique()->safeEmail,
		'password' => $password ?: $password = bcrypt('secret'),
		'remember_token' => str_random(10),
		'language' => $faker->randomElement(['en','es'])
	];
});

$factory->define(App\Models\Admin::class, function (Faker\Generator $faker) {
	return [

	];
});

$factory->define(App\Models\Player::class, function (Faker\Generator $faker) {
	return [

	];
});

$factory->define(App\Models\Match::class, function (Faker\Generator $faker) {

	return [
		'name' => $faker->firstName . "'s Match",
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