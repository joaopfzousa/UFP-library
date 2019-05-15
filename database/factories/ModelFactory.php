<?php

use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'number' => $faker->unique()->numberBetween(1, 999999),
        'password' => Hash::make('teste'),
        'admin' => $faker->boolean,
        'degree' => $faker->numberBetween(1, 3),
    ];
});
