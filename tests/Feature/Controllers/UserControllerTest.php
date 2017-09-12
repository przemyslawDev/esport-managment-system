<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_response_success_test()
    {
        $this->createSystemAdmin();

        $response = $this->get('/users')
            ->assertStatus(200);
    }

    /** @test */
    public function index_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $response = $this->get('/users')
            ->assertRedirect('/403');
    }

    /** @test */
    public function index_as_guest_response_permission_error_test()
    {
        $this->createUser();

        $response = $this->get('/users')
            ->assertRedirect('/403');
    }

    /** @test */
    public function getAll_response_success_test() 
    {
        $this->createSystemAdmin();

        $response = $this->json('get', '/users/get/all')->assertStatus(200);
    }

    /** @test */
    public function getAll_as_admin_response_permission_error_test() 
    {
        $this->createAdmin();

        $this->json('get', '/users/get/all')->assertStatus(403);
    }
    
    /** @test */
    public function getAll_as_guest_response_permission_error_test() 
    {
        $this->createUser();

        $this->json('get', '/users/get/all')->assertStatus(403);
    }

    /** @test */
    public function create_response_success_test()
    {
        $this->createSystemAdmin();

        $response = $this->get('users/create')
            ->assertStatus(200);
    }

    /** @test */
    public function create_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $response = $this->get('users/create')
            ->assertRedirect(403);
    }

    /** @test */
    public function create_as_guest_response_permission_error_test()
    {
        $this->createUser();

        $response = $this->get('users/create')
            ->assertRedirect(403);
    }

    /** @test */
    public function show_response_success_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();
    
        $response = $this->get('/users' . '/' . $user->id)
            ->assertStatus(200);
    }

    /** @test */
    public function show_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $user = factory(User::class)->create();

        $response = $this->get('/users' . '/' . $user->id)
            ->assertRedirect('/403');
    }

    /** @test */
    public function show_as_guest_response_permission_error_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $response = $this->get('/users' . '/' . $user->id)
            ->assertRedirect('/403');
    }

    /** @test */
    public function get_response_success_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();

        $response = $this->json('get', '/users/user' . '/' . $user->id)
            ->assertStatus(200);
    }

    /** @test */
    public function get_as_admin_response_permission_error_test()
    {
        $this->createAdmin();
        
        $user = factory(User::class)->create();

        $response = $this->json('get', '/users/user' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function get_as_guest_response_permission_error_test() 
    {
        $this->createUser();
        
        $user = factory(User::class)->create();

        $response = $this->json('get', '/users/user' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function store_response_success_test()
    {
        $this->createSystemAdmin();

        $data = [
            'email' => 'test@example.com',
            'password' => 'test123'
        ];

        $response = $this->json('post', '/users', $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    /** @test */
    public function store_as_admin_response_permissions_error_test()
    {
        $this->createAdmin();

        $data = [
            'email' => 'test@example.com',
            'password' => 'test123'
        ];

        $response = $this->json('post', '/users', $data)
            ->assertStatus(403);
    }

    /** @test */
    public function store_as_guest_response_permissions_error_test()
    {
        $this->createUser();

        $data = [
            'email' => 'test@example.com',
            'password' => 'test123'
        ];

        $response = $this->json('post', '/users', $data)
            ->assertStatus(403);
    }

    /** @test */
    public function store_response_validation_fail_test() 
    {
        $this->createSystemAdmin();

        $data = collect([
            'email' => 'test@example.com',
            'password' => 'test123'
        ]);

        $this->json('post', '/users', $data->except('email')->toArray())
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.']
                ]
            ]);
        
        $this->json('post', '/users', $data->except('password')->toArray())
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'password' => ['The password field is required.']
            ]
        ]); 
                    
    }

    /** @test */
    public function edit_response_success_test()
    {
        $this->createSystemAdmin();
        
        $user = factory(User::class)->create();

        $response = $this->get('/users' . '/' . $user->id . '/edit')
            ->assertStatus(200);
    }
    
    /** @test */
    public function edit_as_admin_response_permissions_error_test()
    {
        $this->createAdmin();

        $user = factory(User::class)->create();

        $response = $this->get('/users' . '/' . $user->id . '/edit')
            ->assertRedirect('/403');
    }

    /** @test */
    public function edit_as_guest_response_permissions_error_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $response = $this->get('/users' . '/' . $user->id . '/edit')
            ->assertRedirect('/403');
    }

    /** @test */
    public function update_response_success_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();

        $data = [
            'email' => 'test@example.com',
            'roles' => [1,2]
        ];

        $response = $this->json('put', '/users' . '/' . $user->id, $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'test@example.com',
            'avatar' => $user->avatar,
            'active' => 0
        ]);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => 1
        ]);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => 2
        ]);
    }

    /** @test */
    public function update_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $user = factory(User::class)->create();

        $data = [
            'email' => 'test@example.com',
            'avatar' => $user->avatar
        ];

        $response = $this->json('put', '/users' . '/' . $user->id, $data)
            ->assertStatus(403);
    }

    /** @test */
    public function update_as_guest_response_permission_error_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $data = [
            'email' => 'test@example.com',
            'avatar' => $user->avatar
        ];

        $response = $this->json('put', '/users' . '/' . $user->id, $data)
            ->assertStatus(403);
    }

    /** @test */
    public function update_response_validation_fail_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();

        $data = collect([
            'email' => 'test@example.com'
        ]);

        $this->json('put', '/users' . '/' . $user->id, $data->except('email')->toArray())
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.']
                ]
            ]);
    }

    /** @test */
    public function destroy_response_success_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();

        $response = $this->json('delete', '/users' . '/' . $user->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => $user->email
        ]);
    }

    /** @test */
    public function destroy_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $user = factory(User::class)->create();

        $response = $this->json('delete', '/users' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function destroy_as_guest_response_permission_error_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $response = $this->json('delete', '/users' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function activate_response_success_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create(['active' => false]);

        $response = $this->json('get', '/users/activate' . '/' . $user->id)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
            'active' => true
        ]);
    }

    /** @test */
    public function activate_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $user = factory(User::class)->create();

        $response = $this->json('get', '/users/activate' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function activate_as_guest_response_permission_error_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $response = $this->json('get', '/users/activate' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function resetPassword_response_success_test() 
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();

        $response = $this->json('get', '/users/password/reset' . '/' . $user->id)
            ->assertStatus(200);
    }

    /** @test */
    public function resetPassword_as_admin_response_permission_error_test() 
    {
        $this->createAdmin();

        $user = factory(User::class)->create();

        $response = $this->json('get', '/users/password/reset' . '/' . $user->id)
        ->assertStatus(403);
    }

    /** @test */
    public function resetPassword_as_guest_response_permission_error_test() 
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $response = $this->json('get', '/users/password/reset' . '/' . $user->id)
        ->assertStatus(403);
    }
}
