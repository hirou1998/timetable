<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Assignment;
use Faker\Generator as Faker;

$factory->define(Assignment::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence(6),
        'date' => $faker->date(),
        'done_flg' => false,
        'course_id' => function(){
            return factory(Course::class)->create()->id;
        },
        'user_id' => function(){
            return factory(User::class)->create()->id;
        },
    ];
});

// $table->text('body');
//             $table->date('date');
//             $table->boolean('done_flg');
