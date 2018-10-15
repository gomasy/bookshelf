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

        // ok
        $response = $this->actingAs($user)->get('/');
        $response->assertViewIs('dashboard');
        $response->assertStatus(200);

        // redirect to the e-mail confirmation page
        $user->email_verified_at = null;
        $this->actingAs($user)->get('/')->assertRedirect('/email/verify');
    }

    public function testLogout()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)->post('/logout')->assertRedirect('/');
    }
}
