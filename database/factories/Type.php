<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Type;
use Faker\Generator as Faker;

$factory->define(Type::class, function (Faker $faker) {
    return [
        'type1' => '1学期',
        'type2' => '2学期',
        'type3' => '3学期',
        'type4' => '通年'
    ];
});
