<?php

use App\Models\Errors\JsError;
use App\Models\Errors\PhpError;
use Faker\Generator as Faker;

$factory->define(App\Models\Errors\Error::class, function (Faker $faker) {
	if(rand(1,2) < 2){
		$error = factory(PhpError::class)->create();
	} else {
		$error = factory(JsError::class)->create();
	}

	return [
		'page' => $faker->url,
		'user_id' => null,
		'errorable_id' => $error->id,
		'errorable_type' => class_basename($error)
	];
});
