<?php

use Faker\Generator as Faker;

use App\Type;

$factory->define(App\Sensor::class, function (Faker $faker) {
    return [
        'type_id' => factory(App\Type::class),
        'slug'    => str_slug($faker->word)
    ];
});