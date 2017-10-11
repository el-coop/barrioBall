<?php

use App\Models\Errors\JsError;
use App\Models\Errors\PhpError;
use Faker\Generator as Faker;

$factory->define(App\Models\Errors\Error::class, function (Faker $faker, $attributes) {

	return [
		'page' => $faker->url,
		'user_id' => null,
		'errorable_id' => function(){
			return factory(PhpError::class)->create()->id;
		},
		'errorable_type' => 'PHPError'
	];
});
