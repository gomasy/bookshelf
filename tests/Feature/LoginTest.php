<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginForm()
    {
        $response = $this->get('/login');
        $response->assertViewIs('auth.login');
        $response->assertStatus(200);
    }

    public function testLoggedIn()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertViewIs('dashboard');
        $response->assertStatus(200);
    }

    public function testLogout()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)->post('/logout')->assertRedirect('/');
    }
}
