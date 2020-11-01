<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Semester;
use App\User;
use Faker\Generator as Faker;

$factory->define(Semester::class, function (Faker $faker) {
    return [
        'user_id' => function(){
            return factory(User::class)->create()->id;
        },
        'year' => $faker->year(),
        'type' => $faker->randomElement(['1学期', '2学期', '3学期', '通年']),
        'start_date' => $faker->date(),
        'end_date' => $faker->date(),
    ];
});