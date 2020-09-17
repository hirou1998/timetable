<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Course;
use App\User;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(6),
        'user_id' => function(){
            return factory(User::class)->create()->id;
        },
        'color' => '#abc9e3',
        'teacher' => $faker->name(6),
        'type' => $faker->randomElement(['対面', 'オンライン']),
    ];
});