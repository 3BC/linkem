<?php

use Faker\Generator as Faker;

$factory->define(App\Group::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->words(4, true),
        'private' => false,
    ];
});
