<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App;

class AccountTest extends TestCase
{
    use DatabaseMigrations;

    public function testBasicTest()
    {
        $this->get('/account')->assertRedirect('/login');
    }

    public function testLoggedIn()
    {
        $user = factory(App\User::class)->create();
        $this->actingAs($user)->get('/account')->assertStatus(200);
    }

    public function testUpdate()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];

        // success
        $user = factory(App\User::class)->create();
        $this->actingAs($user)
            ->post('/account/update', [
                'email' => 'example@example.com',
                'name' => 'Example' ], $headers)
            ->assertRedirect('/');

        // invaild
        $this->actingAs($user)
            ->post('/account/update', [ 'email' => '' ], $headers)
            ->assertStatus(422);
    }
}
