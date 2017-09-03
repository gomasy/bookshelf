<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testBasicTest()
    {
        $response = $this->get('/register');
        $response->assertViewIs('auth.register');
        $response->assertStatus(200);
    }

    public function testRegister()
    {
        $data = [
            'name' => 'Example',
            'email' => 'example@example.com',
            'password' => 'testpasswd',
            'password_confirmation' => 'testpasswd',
        ];
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];

        // success
        $this->post('/register', $data, $headers)->assertRedirect('/');
        $this->assertDatabaseHas('users', [ 'name' => 'Example' ]);
    }
}
