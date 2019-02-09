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
        $response->assertStatus(200);
        $response->assertJsonFragment([ 'id' => $user->id ]);
    }

    public function testDisplayIndex()
    {
        $user = factory(User::class)->create();
        UserSetting::create([ 'id' => $user->id ]);

        $this->actingAs($user)
             ->get('/settings/display')
             ->assertStatus(200);
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
}
