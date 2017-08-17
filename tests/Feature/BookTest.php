<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App;

class BookTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $book = factory(App\Book::class)->create();
        $user = App\User::find($book->user_id);

        $response = $this->actingAs($user)
            ->get('/list', [ 'X-Requested-With' => 'XMLHttpRequest' ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([ 'data' => [] ]);
    }

    public function testCreate()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = factory(App\User::class)->create();

        // success
        $this->actingAs($user)
            ->post('/create', [ 'code' => '9784873115382' ], $headers)
            ->assertStatus(200);

        // not found
        $this->actingAs($user)
            ->post('/create', [ 'code' => '0000000000000' ], $headers)
            ->assertStatus(404);

        // invalid
        $this->actingAs($user)
            ->post('/create', [ 'code' => '' ], $headers)
            ->assertStatus(422);
    }

    public function testDelete()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $book = factory(App\Book::class)->create();
        $user = App\User::find($book->user_id);

        // success
        $this->actingAs($user)
            ->post('/delete', [ 'id' => $book->id + 1 ], $headers)
            ->assertStatus(200);

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
