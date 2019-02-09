<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\UserSetting;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function testAll()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = factory(User::class)->create();
        UserSetting::create([ 'id' => $user->id ]);

        $response = $this->actingAs($user)->get('/settings/all.json', $headers);
        $response->assertSuccessful();
        $response->assertJsonFragment([ 'id' => $user->id ]);
    }

    public function testDisplayIndex()
    {
        $user = factory(User::class)->create();
        UserSetting::create([ 'id' => $user->id ]);

        $this->actingAs($user)
             ->get('/settings/display')
             ->assertSuccessful();
    }

    public function testDisplayUpdate()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = factory(User::class)->create();
        UserSetting::create([ 'id' => $user->id ]);

        $this->actingAs($user)
             ->post('/settings/display/update', [ 'animation' => 'on' ], $headers)
             ->assertRedirect('/');
        $this->assertDatabaseHas('user_settings', [ 'animation' => 1 ]);
    }

    public function testShelfIndex()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
             ->get('/settings/shelves')
             ->assertSuccessful();
    }

    public function testShelfCreate()
    {
        $headers = [ 'X-Requested-With' => 'XMLHttpRequest' ];
        $user = factory(User::class)->create();

        $this->actingAs($user)
             ->post('/settings/shelves/create', [ 'name' => 'test' ], $headers)
             ->assertSuccessful();
        $this->assertDatabaseHas('bookshelves', [ 'name' => 'test' ]);
    }
}
