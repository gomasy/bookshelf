<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function testBasicTest()
    {
        $this->get('/settings/account')->assertRedirect('/login');
        $this->get('/settings/account/delete')->assertRedirect('/login');
    }

    public function testLoggedIn()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/settings/account');
        $response->assertViewIs('account.update');
        $response->assertSuccessful();

        $response = $this->actingAs($user)->get('/settings/account/delete');
        $response->assertViewIs('account.delete');
        $response->assertSuccessful();
    }

    public function testRedirect()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)->get('/login')->assertRedirect('/');
    }

    public function testUpdate()
    {
        $data = [
            'email' => 'example@example.com',
            'name' => 'Example',
        ];

        // success
        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->post('/settings/account/update', $data)
            ->assertRedirect('/');
        $this->assertDatabaseHas('users', $data);

        // invaild
        $this->actingAs($user)
            ->post('/settings/account/update', [ 'email' => '', 'name' => '' ])
            ->assertSessionHasErrors([ 'email', 'name' ]);
    }

    public function testDelete()
    {
        $password = 'testpasswd';

        // success
        $user = factory(User::class)->create([ 'password' => bcrypt($password) ]);
        $this->actingAs($user)
            ->post('/settings/account/delete', [ 'password' => $password ])
            ->assertRedirect('/');
        $this->assertDatabaseMissing('users', [ 'id' => 1 ]);

        // fail
        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->post('/settings/account/delete', [ 'password' => $password ])
            ->assertSuccessful();

        // invaild
        $this->actingAs($user)
            ->post('/settings/account/delete', [ 'password' => '' ])
            ->assertSessionHasErrors('password');
    }
}
