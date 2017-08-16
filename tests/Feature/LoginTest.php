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
    }

    public function testLoggedIn()
    {
        $user = factory(App\User::class)->create();
        $this->actingAs($user)->get('/')->assertStatus(200);
    }

    public function testLogout()
    {
        $user = factory(App\User::class)->create();
        $this->actingAs($user)->post('/logout')->assertRedirect('/');
    }
}
