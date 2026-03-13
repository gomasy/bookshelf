<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Bookshelf;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'bookshelf_id' => Bookshelf::factory(),
            'title'        => fake()->title(),
            'volume'       => fake()->randomDigit(),
            'authors'      => fake()->name(),
            'isbn'         => fake()->isbn13(),
            'jpno'         => fake()->ean8(),
            'ndl_url'      => fake()->url(),
        ];
    }
}
