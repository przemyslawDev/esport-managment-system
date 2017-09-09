<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;
use App\User;
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
            'password' => bcrypt('test')
        ]);

        $data = [
            'email' => 'test@exapmle.com',
            'password' => 'test'
        ];

        $response = $this->post('/login', $data)->assertStatus(302);
    }
}
