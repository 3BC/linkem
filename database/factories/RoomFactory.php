<?php

use Faker\Generator as Faker;

$factory->define(App\Room::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->words(4, true),
        'private' => false,
        'owner_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});
