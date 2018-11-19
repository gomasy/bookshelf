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

        // title=foo, sortby=id, order=desc
        $response = $this->actingAs($user)
            ->get('/list.json?title=foo&sort=id&order=desc', [ 'X-Requested-With' => 'XMLHttpRequest' ]);
        $response->assertSuccessful();
        $this->assertEquals(count($response->original['data']), 25);
        $this->assertEquals($response->original['total'], 25);
        $this->assertEquals($response->original['data'][0]->id, 25);

        // title=bar, limit=50
        $response = $this->actingAs($user)
            ->get('/list.json?offset=0&limit=10&title=bar', [ 'X-Requested-With' => 'XMLHttpRequest' ]);
        $response->assertSuccessful();
        $this->assertEquals(count($response->original['data']), 10);
        $this->assertEquals($response->original['total'], 25);
    }

    public function testCreate()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = factory(User::class)->create();

        // success
        $id = $this->actingAs($user)->get('/fetch?code=9784873115382')->headers->get('X-Request-Id');
        $this->actingAs($user)->post('/create', [ 'id' => $id ], $headers)->assertSuccessful();
        $this->assertDatabaseHas('books', [ 'isbn' => '9784873115382' ]);

        // success (isbn10)
        $id = $this->actingAs($user)->get('/fetch?code=4000801139')->headers->get('X-Request-Id');
        $this->actingAs($user)->post('/create', [ 'id' => $id ], $headers)->assertSuccessful();
        $this->assertDatabaseHas('books', [ 'isbn' => '9784000801133' ]);

        // dups
        $this->actingAs($user)->get('/fetch?code=4873115388')->assertStatus(409);

        // not found
        $this->actingAs($user)->get('/fetch?code=1234567890123', $headers)->assertStatus(404);

        // invalid
        $this->actingAs($user)->get('/fetch?code=', $headers)->assertSessionHasErrors('code');
    }

    public function testFetchImage()
    {
        $user = factory(User::class)->create();

        // normal
        $response = $this->actingAs($user)->get('/images/P/4774158798.09.LZZZZZZZ');
        $response->assertHeader('Content-Type', 'image/jpeg');
        $response->assertStatus(200);

        // missing (normal)
        $response = $this->actingAs($user)->get('/images/P/4840234884.09.LZZZZZZZ');
        $response->assertHeader('Content-Type', 'image/jpeg');
        $response->assertStatus(200);

        // missing
        $response = $this->actingAs($user)->get('/images/P/missing.large.jpg');
        $response->assertHeader('Content-Type', 'image/jpeg');
        $response->assertStatus(200);
    }

    public function testEdit()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $data = [
            'id' => 1,
            'title' => 'Example',
            'volume' => 'Example',
            'authors' => 'Example',
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
