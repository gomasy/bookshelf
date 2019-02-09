<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testLinkRequest()
    {
        $response = $this->get('/password/reset');
        $response->assertViewIs('auth.passwords.email');
        $response->assertSuccessful();
    }

    public function testReset()
    {
        $response = $this->get('/password/reset/test');
        $response->assertViewIs('auth.passwords.reset');
        $response->assertSuccessful();
    }
}
