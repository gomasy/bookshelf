<?php

use Faker\Generator as Faker;
use App\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'next_id'        => 1,
        'remember_token' => str_random(10),
    ];
});
