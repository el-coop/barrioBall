<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Message::class, function (Faker $faker) {
    return [
    	'user_id' => 1,
		'conversation_id' => 1,
        'text' => 'This is a demo message'
    ];
});
