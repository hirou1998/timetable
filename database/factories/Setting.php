<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Setting;
use App\User;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Setting::class, function (Faker $faker) {
    return [
        'user_id' => function(){
            return factory(User::class)->create()->id;
        },
        'start_date' => '2020-04-07',
        'end_date' => '2020-08-04',
        'first_start_time' => '09:00:00',
        'first_end_time' => '10:30:00',
        'second_start_time' => '10:40:00',
        'second_end_time' => '12:10:00',
        'third_start_time' => '13:00:00',
        'third_end_time' => '14:30:00',
        'fourth_start_time' => '14:45:00',
        'fourth_end_time' => '16:15:00',
        'fifth_start_time' => '16:30:00',
        'fifth_end_time' => '18:00:00',
        'sixth_start_time' => '18:15:00',
        'sixth_end_time' => '19:45:00',
    ];
});