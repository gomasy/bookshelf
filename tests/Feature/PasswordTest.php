<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PasswordTest extends TestCase
{
    use DatabaseMigrations;

    public function testLinkRequest()
    {
        $response = $this->get('/password/reset');
        $response->assertViewIs('auth.passwords.email');
        $response->assertStatus(200);
    }

    public function testReset()
    {
        $response = $this->get('/password/reset/test');
        $response->assertViewIs('auth.passwords.reset');
        $response->assertStatus(200);
    }
}
