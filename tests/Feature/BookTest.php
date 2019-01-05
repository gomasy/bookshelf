<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Book;
use App\Bookshelf;
use App\User;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = factory(User::class)->create();
        $shelf = Bookshelf::create([ 'user_id' => $user->id, 'name' => 'default' ]);

        for ($i = 1; $i <= 50; $i++) {
            $data = [
                'id' => $i,
                'bookshelf_id' => $shelf->id,
            ];
            $data = array_merge($data, [ 'title' => ($i <= 25 ? 'foo' : 'bar') ]);

            factory(Book::class)->create($data);
        }

        // all
        $response = $this->actingAs($user)->get('/list.json', $headers);
        $response->assertSuccessful();
        $this->assertEquals(count($response->original['data']), 50);
        $this->assertEquals($response->original['total'], 50);

        // title=foo, sortby=id, order=desc
        $response = $this->actingAs($user)
                         ->get('/list.json?title=foo&sort=id&order=desc', $headers);
        $response->assertSuccessful();
        $this->assertEquals(count($response->original['data']), 25);
        $this->assertEquals($response->original['total'], 25);
        $this->assertEquals($response->original['data'][0]->id, 25);

        // title=bar, limit=50
        $response = $this->actingAs($user)
                         ->get('/list.json?offset=0&limit=10&title=bar', $headers);
        $response->assertSuccessful();
        $this->assertEquals(count($response->original['data']), 10);
        $this->assertEquals($response->original['total'], 25);
    }

    public function testCreate()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = factory(User::class)->create();
        $shelf = factory(Bookshelf::class)->create([ 'user_id' => $user->id ]);

        // success
        $books = $this->actingAs($user)
                      ->get("/fetch?type=code&sid={$shelf->id}&p=9784873115382", $headers)
                      ->original;

        $this->actingAs($user)
             ->post('/create', [ 'sid' => $shelf->id, 'p' => $books ], $headers)
             ->assertSuccessful();
        $this->assertDatabaseHas('books', [ 'isbn' => '9784873115382' ]);

        // success (isbn10)
        $books = $this->actingAs($user)
                      ->get("/fetch?type=code&sid={$shelf->id}&p=4000801139", $headers)
                      ->original;

        $this->actingAs($user)
             ->post('/create', [ 'sid' => $shelf->id, 'p' => $books ], $headers)
             ->assertSuccessful();
        $this->assertDatabaseHas('books', [ 'isbn' => '9784000801133' ]);

        // success (jpno)
        $books = $this->actingAs($user)
                      ->get("/fetch?type=code&sid={$shelf->id}&p=22222222", $headers)
                      ->original;

        $this->actingAs($user)
             ->post('/create', [ 'sid' => $shelf->id, 'p' => $books ], $headers)
             ->assertSuccessful();
        $this->assertDatabaseHas('books', [ 'jpno' => '22222222' ]);

        // dups
        $this->actingAs($user)
             ->get("/fetch?type=code&sid={$shelf->id}&p=4873115388", $headers)
             ->assertStatus(409);

        // not found
        $this->actingAs($user)
             ->get("/fetch?type=code&sid={$shelf->id}&p=1234567890128", $headers)
             ->assertStatus(404);

        // invalid
        $this->actingAs($user)
             ->get("/fetch?type=code&sid={$shelf->id}&p=1234567890123", $headers)
             ->assertSessionHasErrors('p');

        $this->actingAs($user)
             ->get("/fetch?type=code&sid={$shelf->id}&p=", $headers)
             ->assertSessionHasErrors('p');
    }

    public function testFetchImage()
    {
        $user = factory(User::class)->create();
        factory(Bookshelf::class)->create([ 'user_id' => $user->id ]);

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
        $user = factory(User::class)->create();
        $shelf = factory(Bookshelf::class)->create([ 'user_id' => $user->id ]);
        $book = factory(Book::class)->create([ 'bookshelf_id' => $shelf->id ]);
        $data = [
            'id' => $book->id,
            'title' => 'Example',
            'volume' => 'Example',
            'authors' => 'Example',
        ];

        // success
        $this->actingAs($user)
             ->post('/edit', $data, $headers)
             ->assertSuccessful();
        $this->assertDatabaseHas('books', $data);
    }

    public function testDelete()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = factory(User::class)->create();
        $shelf = factory(Bookshelf::class)->create([ 'user_id' => $user->id ]);
        $book = factory(Book::class)->create([ 'bookshelf_id' => $shelf->id ]);

        // success
        $this->actingAs($user)
             ->post('/delete', [ 'ids' => [ $book->id ] ], $headers)
             ->assertStatus(204);
        $this->assertDatabaseMissing('books', [ 'id' => $book->id ]);

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
