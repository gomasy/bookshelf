<?php

namespace Database\Factories;

use App\Models\Bookshelf;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookshelfFactory extends Factory
{
    protected $model = Bookshelf::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => 'default',
        ];
    }
}
