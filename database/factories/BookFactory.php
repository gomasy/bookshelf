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
    return [
        'bookshelf_id' => function () {
            return factory(\App\Bookshelf::class)->create();
        },
        'title'        => $faker->title,
        'volume'       => $faker->randomDigit,
        'authors'      => $faker->name,
        'isbn'         => $faker->isbn13(),
        'jpno'         => $faker->ean8(),
        'ndl_url'      => $faker->url,
    ];
});
