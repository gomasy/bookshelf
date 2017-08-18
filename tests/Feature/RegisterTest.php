<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    public function testBasicTest()
    {
        $this->get('/register')->assertStatus(200);
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
        $this->post('/register', $data, $headers)
            ->assertRedirect('/');
    }
}
