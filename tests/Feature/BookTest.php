<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $book = factory(App\Book::class)->create();
        $user = App\User::find($book->user_id);

        $response = $this->actingAs($user)
            ->get('/list', [ 'X-Requested-With' => 'XMLHttpRequest' ]);
        $response->assertJsonStructure([ 'data' => [] ]);
        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = factory(App\User::class)->create();

        // success
        $this->actingAs($user)
            ->post('/create', [ 'code' => '9784873115382' ], $headers)
            ->assertStatus(200);
        $this->assertDatabaseHas('books', [ 'isbn' => '9784873115382' ]);

        // success (isbn10)
        $this->actingAs($user)
            ->post('/create', [ 'code' => '4000801139' ], $headers)
            ->assertStatus(200);
        $this->assertDatabaseHas('books', [ 'isbn' => '9784000801133' ]);

        // dups
        $this->actingAs($user)
            ->post('/create', [ 'code' => '4873115388' ], $headers)
            ->assertStatus(409);

        // not found
        $this->actingAs($user)
            ->post('/create', [ 'code' => '0000000000000' ], $headers)
            ->assertStatus(404);

        // invalid
        $this->actingAs($user)
            ->post('/create', [ 'code' => '' ], $headers)
            ->assertStatus(422);
    }

    public function testEdit()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $data = [
            'id' => 1,
            'title' => 'Example',
            'volume' => 'Example',
            'authors' => 'Example',
            'published_date' => '1970-01-01',
        ];
        $book = factory(App\Book::class)->create();
        $user = App\User::find($book->user_id);

        // success
        $this->actingAs($user)
            ->post('/edit', $data, $headers)
            ->assertStatus(200);
        $this->assertDatabaseHas('books', $data);
    }

    public function testDelete()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $book = factory(App\Book::class)->create();
        $user = App\User::find($book->user_id);

        // success
        $this->actingAs($user)
            ->post('/delete', [ 'id' => '1' ], $headers)
            ->assertStatus(200);
        $this->assertDatabaseMissing('books', [ 'id' => '1' ]);

        // not found
        $this->actingAs($user)
            ->post('/delete', [ 'id' => '0' ], $headers)
            ->assertStatus(404);

        // invalid
        $this->actingAs($user)
            ->post('/delete', [ 'id' => '' ], $headers)
            ->assertStatus(422);
    }
}
