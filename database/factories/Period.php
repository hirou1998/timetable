<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Period;
use App\User;
use App\Course;
use App\Semester;
use Faker\Generator as Faker;

$factory->define(Period::class, function (Faker $faker) {
    return [
        'course_id' => function(){
            return factory(Course::class)->create()->id;
        },
        'user_id' => function(){
            return factory(User::class)->create()->id;
        },
        'semester_id' => function(){
            return factory(User::class)->create()->id;
        },
        'day_of_week' => $faker->numberBetween(1, 6),
        'period' => $faker->numberBetween(1, 6),
    ];
});
