<?php

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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Book::class, function (Faker\Generator $faker) {
    $user = factory(App\User::class)->create();

    return [
        'id' => $user->next_id,
        'user_id' => $user->id,
        'title' => $faker->title,
        'title_ruby' => $faker->title,
        'volume' => $faker->randomDigit,
        'authors' => $faker->name,
        'isbn' => $faker->isbn13(),
        'jpno' => $faker->ean8(),
        'published_date' => $faker->date,
        'ndl_url' => $faker->url,
    ];
});
