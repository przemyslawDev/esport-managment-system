<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_response_success_test()
    {
        $this->createUser();

        $response = $this->get('/users')
            ->assertStatus(200);
    }

    /** @test */
    public function index_as_guest_response_permission_error_test()
    {
        $response = $this->get('/users')
            ->assertStatus(302);
    }

    /** @test */
    public function show_response_success_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();
    
        $response = $this->get('/users' . '/' . $user->id)
            ->assertStatus(200);
    }

    /** @test */
    public function show_as_guest_response_permission_error_test()
    {
        $user = factory(User::class)->create();

        $response = $this->get('/users' . '/' . $user->id)
            ->assertStatus(302);
    }

    /** @test */
    public function create_response_success_test()
    {
        $this->createUser();

        $response = $this->get('users/create')
            ->assertStatus(200);
    }

    /** @test */
    public function create_as_guest_response_permission_error_test()
    {
        $response = $this->get('users/create')
            ->assertStatus(302);
    }

    /** @test */
    public function store_response_success_test()
    {
        $this->createUser();

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
    public function store_as_guest_response_permissions_error_test()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'test123'
        ];

        $response = $this->json('post', '/users', $data)
            ->assertStatus(401);
    }

    /** @test */
    public function store_response_validation_fail_test() 
    {
        $this->createUser();

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
        $this->createUser();
        
        $user = factory(User::class)->create();

        $response = $this->get('/users' . '/' . $user->id . '/edit')
            ->assertStatus(200);
    }
    
    /** @test */
    public function edit_as_guest_response_permissions_error_test()
    {
        $user = factory(User::class)->create();

        $response = $this->get('/users' . '/' . $user->id . '/edit')
            ->assertStatus(302);
    }

    /** @test */
    public function update_response_success_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $data = [
            'email' => 'test@example.com'
        ];

        $response = $this->json('put', '/users' . '/' . $user->id, $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'test@example.com',
            'avatar' => $user->avatar,
            'active' => 0
        ]);
    }

    /** @test */
    public function update_as_guest_response_permission_error_test()
    {
        $user = factory(User::class)->create();

        $data = [
            'email' => 'test@example.com',
            'avatar' => $user->avatar
        ];

        $response = $this->json('put', '/users' . '/' . $user->id, $data)
            ->assertStatus(401);
    }

    /** @test */
    public function update_response_validation_fail_test()
    {
        $this->createUser();

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
    public function delete_response_success_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $response = $this->json('delete', '/users' . '/' . $user->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => $user->email
        ]);
    }

    /** @test */
    public function delete_as_guest_response_permission_error_test()
    {
        $user = factory(User::class)->create();

        $response = $this->json('delete', '/users' . '/' . $user->id)
            ->assertStatus(401);
    }
}
