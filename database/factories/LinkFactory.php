<?php

use Faker\Generator as Faker;

$factory->define(App\Link::class, function (Faker $faker) {
    return [
        'url' => $faker->url,
        'name' => $faker->word,
        'description' => $faker->words(4, true),
        'group_id' => 1,
        'user_id' => 1
    ];
});
