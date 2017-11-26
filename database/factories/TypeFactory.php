<?php

use Faker\Generator as Faker;

use App\Type;

$factory->define(App\Type::class, function (Faker $faker) {
    return [
        'slug' => str_slug($faker->word)
    ];
});