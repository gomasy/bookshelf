<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;

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
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/settings/account');
        $response->assertViewIs('settings.account.update');
        $response->assertSuccessful();

        $response = $this->actingAs($user)->get('/settings/account/delete');
        $response->assertViewIs('settings.account.delete');
        $response->assertSuccessful();
    }

    public function testRedirect()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/login')->assertRedirect('/');
    }

    public function testUpdate()
    {
        $password = 'testpasswd';
        $data = [
            'email' => 'example@example.com',
            'name' => 'Example',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        // success
        $user = User::factory()->create();
        $this->actingAs($user)
             ->post('/settings/account/update', $data)
             ->assertRedirect('/');
        $this->assertDatabaseHas('users', array_slice($data, 0, 2));

        // invaild
        $this->actingAs($user)
             ->post('/settings/account/update', [ 'email' => '', 'name' => '' ])
             ->assertSessionHasErrors([ 'email', 'name' ]);
    }

    public function testDelete()
    {
        $password = 'testpasswd';

        // success
        $user = User::factory()->create([ 'password' => bcrypt($password) ]);
        $this->actingAs($user)
             ->post('/settings/account/delete', [ 'password' => $password ])
             ->assertRedirect('/');
        $this->assertDatabaseMissing('users', [ 'id' => 1 ]);

        // fail
        $user = User::factory()->create();
        $this->actingAs($user)
             ->post('/settings/account/delete', [ 'password' => $password ])
             ->assertSuccessful();

        // invaild
        $this->actingAs($user)
             ->post('/settings/account/delete', [ 'password' => '' ])
             ->assertSessionHasErrors('password');
    }
}
