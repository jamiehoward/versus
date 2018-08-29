<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Hero::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'hp' => rand(1,10),
        'level' => rand(1,10),
        'attack_name' => $faker->word,
        'attack_points' => rand(1,10),
        'heal_name' => $faker->word,
        'heal_points' => rand(1,10)
    ];
});
