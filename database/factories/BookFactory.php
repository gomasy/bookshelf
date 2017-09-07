<?php

use Faker\Generator as Faker;

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

$factory->define(\App\Book::class, function (Faker $faker) {
    $user = factory(\App\User::class)->create();

    return [
        'id'             => $user->next_id,
        'user_id'        => $user->id,
        'title'          => $faker->title,
        'volume'         => $faker->randomDigit,
        'authors'        => $faker->name,
        'isbn'           => $faker->isbn13(),
        'jpno'           => $faker->ean8(),
        'published_date' => $faker->date,
        'ndl_url'        => $faker->url,
    ];
});
