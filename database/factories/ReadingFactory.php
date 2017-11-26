<?php

use Faker\Generator as Faker;

$factory->define(App\Reading::class, function (Faker $faker) {
    return [
        'sensor_id' => factory(App\Sensor::class)->create(),
        'value'     => $faker->randomFloat(2, 0, 100)
    ];
});
