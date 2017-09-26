<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;
use App\User;
use App\Role;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function showLoginForm_response_success_test()
    {
        $this->get('/')->assertSuccessful()->assertViewIs('auth.login');
    }

    /** @test */
    public function login_admin_response_success_test()
    {
        $user = factory(User::class)->create([
            'email' => 'test_1@example.com',
            'password' => bcrypt('test123'),
            'active' => 1,
            'confirmed' => 1
        ]);
        
        $role = Role::where('name', 'admin')->first();

        $user->attachRole($role);

        $data = [
            'email' => 'test_1@example.com',
            'password' => 'test123'
        ];

        $this->post('/login', $data)->assertRedirect('/dashboard');
  
        $this->assertEquals(Auth::id(), $user->id);
        $this->assertTrue(Auth::user()->hasRole('admin'));
    }

    /** @test */
    public function login_systemadmin_response_success_test()
    {
        $user = factory(User::class)->create([
            'email' => 'test_2@example.com',
            'password' => bcrypt('test123'),
            'active' => 1,
            'confirmed' => 1
        ]);
        
        $role = Role::where('name', 'system_admin')->first();

        $user->attachRole($role);

        $data = [
            'email' => 'test_2@example.com',
            'password' => 'test123'
        ];

        $this->post('/login', $data)->assertRedirect('/dashboard');
  
        $this->assertEquals(Auth::id(), $user->id);
        $this->assertTrue(Auth::user()->hasRole('system_admin'));
    }

    /** @test */
    public function login_manager_response_success_test()
    {
        $user = factory(User::class)->create([
            'email' => 'test_3@example.com',
            'password' => bcrypt('test123'),
            'active' => 1,
            'confirmed' => 1
        ]);

        $role = Role::where('name', 'manager')->first();

        $user->attachRole($role);

        $data = [
            'email' => 'test_3@example.com',
            'password' => 'test123'
        ];

        $this->post('/login', $data)->assertRedirect('/dashboard');

        $this->assertEquals(Auth::id(), $user->id);
        $this->assertTrue(Auth::user()->hasRole('manager'));
    }

    /** @test */
    public function login_response_unactive_error_test()
    {
        $user = factory(User::class)->create([
            'email' => 'test_4@example.com',
            'password' => bcrypt('test123'),
            'active' => 0,
            'confirmed' => 1
        ]);

        $data = [
            'email' => 'test_4@example.com',
            'password' => 'test123'
        ];

        $this->post('/login', $data)->assertRedirect('/');

        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function login_response_not_verified_error_test()
    {
        $user = factory(User::class)->create([
            'email' => 'test_5@example.com',
            'password' => bcrypt('test123'),
            'active' => 1,
            'confirmed' => 0
        ]);

        $data = [
            'email' => 'test_5@example.com',
            'password' => 'test123'
        ];

        $this->post('/login', $data)->assertRedirect('/');

        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function logout_response_success_test()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $this->assertTrue(Auth::check());

        $this->get('/logout')->assertRedirect('/');

        $this->assertFalse(Auth::check());
    }
}
