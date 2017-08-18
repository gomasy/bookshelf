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
        $this->get('/account/delete')->assertRedirect('/login');
    }

    public function testLoggedIn()
    {
        $user = factory(App\User::class)->create();
        $this->actingAs($user)->get('/account')->assertStatus(200);
        $this->actingAs($user)->get('/account/delete')->assertStatus(200);
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

    public function testDelete()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $password = 'testpasswd';

        // success
        $user = factory(App\User::class)->create([ 'password' => bcrypt($password) ]);
        $this->actingAs($user)
            ->post('/account/delete', [ 'password' => $password ], $headers)
            ->assertRedirect('/');

        // fail
        $user = factory(App\User::class)->create();
        $this->actingAs($user)
            ->post('/account/delete', [ 'password' => $password ], $headers)
            ->assertStatus(200);

        // invaild
        $this->actingAs($user)
            ->post('/account/delete', [ 'password' => '' ], $headers)
            ->assertStatus(422);
    }
}
