<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\UserSetting;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function testAll()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = User::factory()->create();
        UserSetting::create([ 'id' => $user->id ]);

        $response = $this->actingAs($user)->get('/settings/all.json', $headers);
        $response->assertSuccessful();
        $response->assertJsonFragment([ 'id' => $user->id ]);
    }

    public function testDisplayIndex()
    {
        $user = User::factory()->create();
        UserSetting::create([ 'id' => $user->id ]);

        $this->actingAs($user)
             ->get('/settings/display')
             ->assertSuccessful();
    }

    public function testDisplayUpdate()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = User::factory()->create();
        UserSetting::create([ 'id' => $user->id ]);

        $this->actingAs($user)
             ->post('/settings/display/update', [ 'animation' => 'on' ], $headers)
             ->assertRedirect('/');
        $this->assertDatabaseHas('user_settings', [ 'animation' => 1 ]);
    }

    public function testShelfIndex()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->get('/settings/shelves')
             ->assertSuccessful();
    }

    public function testShelfCreate()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/settings/shelves/create', [ 'name' => 'test' ], $headers)
             ->assertSuccessful();
        $this->assertDatabaseHas('bookshelves', [ 'name' => 'test' ]);

        // fail (403)
        $this->actingAs($user)
             ->post('/settings/shelves/create', [ 'name' => 'test' ], $headers)
             ->assertStatus(403);
    }
}
