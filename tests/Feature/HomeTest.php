<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function testBasicTest()
    {
        $this->get('/')->assertStatus(200);
    }
}
