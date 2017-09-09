<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dashboard_response_success_test()
    {
        $user = factory(User::class)->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test')
        ]);

        $this->actingAs($user);

        $response = $this->get('/dashboard');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function dashboard_response_permission_error_test()
    {
        $response = $this->get('/dashboard')->assertStatus(302)->assertRedirect('/');
    }
}
