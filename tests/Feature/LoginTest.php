<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    public function testBasicTest()
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function testLoggedIn()
    {
        $user = factory(\App\User::class)->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
    }

    public function testLogout()
    {
        $user = factory(\App\User::class)->create();
        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/');
    }
}
