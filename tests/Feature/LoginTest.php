<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    public function testBasicTest()
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/', [ 'X-Requested-With' => 'XMLHttpRequest' ])
            ->assertStatus(401);
    }

    public function testLoggedIn()
    {
        $user = [
            'email' => 'example@example.com',
            'name' => 'Example',
            'password' => 'testtest',
        ];

        factory(App\User::class)->create($user);
        $this->post('/login', $user)->assertRedirect('/');
    }

    public function testLogout()
    {
        $user = factory(App\User::class)->create();
        $this->actingAs($user)->post('/logout')->assertRedirect('/');
    }
}
