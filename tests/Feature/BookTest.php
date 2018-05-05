<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Book;
use App\User;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $book = factory(Book::class)->create([ 'title' => 'foo' ]);
        $user = User::find($book->user_id);

        for ($i = 2; $i <= 50; $i++) {
            $data = [
                'id' => $i,
                'user_id' => $book->user_id,
            ];
            $data = array_merge($data, [ 'title' => ($i <= 25 ? 'foo' : 'bar') ]);

            factory(Book::class)->create($data);
        }

        // all
        $response = $this->actingAs($user)
            ->get('/list.json', [ 'X-Requested-With' => 'XMLHttpRequest' ]);
        $response->assertSuccessful();
        $this->assertEquals(count($response->original['data']), 50);
        $this->assertEquals($response->original['total'], 50);

        // title=foo
        $response = $this->actingAs($user)
            ->get('/list.json?title=foo', [ 'X-Requested-With' => 'XMLHttpRequest' ]);
        $response->assertSuccessful();
        $this->assertEquals(count($response->original['data']), 25);
        $this->assertEquals($response->original['total'], 25);

        // title=bar, limit=50
        $response = $this->actingAs($user)
            ->get('/list.json?offset=0&limit=10&title=bar', [ 'X-Requested-With' => 'XMLHttpRequest'] );
        $response->assertSuccessful();
        $this->assertEquals(count($response->original['data']), 10);
        $this->assertEquals($response->original['total'], 25);
    }

    public function testCreate()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = factory(User::class)->create();

        // success
        $this->actingAs($user)
            ->post('/create', [ 'code' => '9784873115382' ], $headers)
            ->assertSuccessful();
        $this->assertDatabaseHas('books', [ 'isbn' => '9784873115382' ]);

        // success (isbn10)
        $this->actingAs($user)
            ->post('/create', [ 'code' => '4000801139' ], $headers)
            ->assertSuccessful();
        $this->assertDatabaseHas('books', [ 'isbn' => '9784000801133' ]);

        // dups
        $this->actingAs($user)
            ->post('/create', [ 'code' => '4873115388' ], $headers)
            ->assertStatus(409);

        // not found
        $this->actingAs($user)
            ->post('/create', [ 'code' => '1234567890123' ], $headers)
            ->assertStatus(404);

        // invalid
        $this->actingAs($user)
            ->post('/create', [ 'code' => '' ], $headers)
            ->assertSessionHasErrors('code');
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
        $book = factory(Book::class)->create();
        $user = User::find($book->user_id);

        // success
        $this->actingAs($user)
            ->post('/edit', $data, $headers)
            ->assertSuccessful();
        $this->assertDatabaseHas('books', $data);
    }

    public function testDelete()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $book = factory(Book::class)->create();
        $user = User::find($book->user_id);

        // success
        $this->actingAs($user)
            ->post('/delete', [ 'ids' => [ 1 ] ], $headers)
            ->assertStatus(204);
        $this->assertDatabaseMissing('books', [ 'id' => '1' ]);

        // bad request
        $this->actingAs($user)
            ->post('/delete', [ 'ids' => [ 0 ] ], $headers)
            ->assertStatus(400);

        // invalid
        $this->actingAs($user)
            ->post('/delete', [ 'ids' => '' ], $headers)
            ->assertSessionHasErrors('ids');
    }
}
