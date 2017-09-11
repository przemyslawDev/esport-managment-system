<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;
use App\User;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function showLoginForm_response_success_test()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function login_response_success_test()
    {
        $user = factory(User::class)->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test123'),
            'active' => 1
        ]);

        $data = [
            'email' => 'test@example.com',
            'password' => 'test123'
        ];

        $response = $this->post('/login', $data)
            ->assertStatus(302);
  
        $this->assertEquals(Auth::id(), $user->id);
    }

    /** @test */
    public function login_response_unactive_error_test()
    {
        $user = factory(User::class)->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test123'),
            'active' => 0
        ]);

        $data = [
            'email' => 'test@example.com',
            'password' => 'test123'
        ];

        $response = $this->post('/login', $data)
            ->assertStatus(302);

        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function logout_response_success_test()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $this->assertTrue(Auth::check());

        $response = $this->get('/logout')
            ->assertStatus(302)
            ->assertRedirect('/');

        $this->assertFalse(Auth::check());
    }
}
