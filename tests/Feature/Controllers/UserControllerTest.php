<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\User;
use App\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_response_success_test()
    {
        $this->createSystemAdmin();

        $this->get('/users')->assertStatus(200);
    }

    /** @test */
    public function index_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $this->get('/users')->assertRedirect('/403');
    }

    /** @test */
    public function index_as_guest_response_permission_error_test()
    {
        $this->createUser();

        $this->get('/users')->assertRedirect('/403');
    }

    /** @test */
    public function getAll_response_success_test() 
    {
        $this->createSystemAdmin();

        $count = User::count();

        $response = $this->json('get', '/users/get/all')
            ->assertStatus(200)->decodeResponseJson();

        $response_count = count($response);
        $this->assertEquals($count, $response_count);
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

        $this->get('users/create')->assertStatus(200);
    }

    /** @test */
    public function create_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $this->get('users/create')->assertRedirect(403);
    }

    /** @test */
    public function create_as_guest_response_permission_error_test()
    {
        $this->createUser();

        $this->get('users/create')->assertRedirect(403);
    }

    /** @test */
    public function show_response_success_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();
    
        $this->get('/users' . '/' . $user->id)->assertStatus(200);
    }

    /** @test */
    public function show_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $user = factory(User::class)->create();

        $this->get('/users' . '/' . $user->id)->assertRedirect('/403');
    }

    /** @test */
    public function show_as_guest_response_permission_error_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $this->get('/users' . '/' . $user->id)->assertRedirect('/403');
    }

    /** @test */
    public function get_response_success_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();

        $response = $this->json('get', '/users/user' . '/' . $user->id)
            ->assertStatus(200)->decodeResponseJson();

        $this->assertNotEmpty($response);
    }

    /** @test */
    public function get_as_admin_response_permission_error_test()
    {
        $this->createAdmin();
        
        $user = factory(User::class)->create();

        $this->json('get', '/users/user' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function get_as_guest_response_permission_error_test() 
    {
        $this->createUser();
        
        $user = factory(User::class)->create();

        $this->json('get', '/users/user' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function store_user_response_success_test()
    {
        $this->createSystemAdmin();

        $data = [
            'email' => 'test@example.com',
            'password' => 'test123',
            'roles' => [1],
            'type' => 'none'
        ];

        $this->json('post', '/users', $data)->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);

        $user = User::where('email', 'test@example.com')->first();

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => 1
        ]);
    }

    /** @test */
    public function store_user_employee_response_success_test()
    {
        $this->createSystemAdmin();

        $data = [
            'email' => 'test@example.com',
            'password' => 'test123',
            'roles' => [1],
            'type' => 'employee',
            'firstname' => 'test',
            'lastname' => 'test',
            'office' => 'test',
            'birthdate' => Carbon::now()->subYears(20)->toDateString()
        ];

        $this->json('post', '/users', $data)->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);

        $user = User::where('email', 'test@example.com')->first();

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => 1
        ]);

        $this->assertDatabaseHas('employees', [
            'user_id' => $user->id,
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'office' => $data['office']
        ]);
    }

    /** @test */
    public function store_as_admin_response_permissions_error_test()
    {
        $this->createAdmin();

        $data = [];

        $this->json('post', '/users', $data)->assertStatus(403);
    }

    /** @test */
    public function store_as_guest_response_permissions_error_test()
    {
        $this->createUser();

        $data = [];

        $this->json('post', '/users', $data)->assertStatus(403);
    }

    /** @test */
    public function store_user_response_validation_fail_test() 
    {
        $this->createSystemAdmin();

        $data = collect([
            'email' => 'test@example.com',
            'password' => 'test123',
            'roles' => 1,
            'type' => 'none'
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

        $this->json('post', '/users', $data->except('roles')->toArray())
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'roles' => ['The roles field is required.']
            ]
        ]);

        $this->json('post', '/users', $data->except('type')->toArray())
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'type' => ['The type field is required.']
            ]
        ]);

    }

     /** @test */
     public function store_user_employee_response_validation_fail_test() 
     {
         $this->createSystemAdmin();
 
         $data = collect([
             'email' => 'test@example.com',
             'password' => 'test123',
             'roles' => 1,
             'type' => 'employee',
             'firstname' => 'test',
             'lastname' => 'test',
             'office' => 'test',
             'birthdate' => Carbon::now()->subYears(20)->toDateString()
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
 
         $this->json('post', '/users', $data->except('roles')->toArray())
         ->assertStatus(422)
         ->assertJson([
             'message' => 'The given data was invalid.',
             'errors' => [
                 'roles' => ['The roles field is required.']
             ]
         ]);

         $this->json('post', '/users', $data->except('type')->toArray())
         ->assertStatus(422)
         ->assertJson([
             'message' => 'The given data was invalid.',
             'errors' => [
                 'type' => ['The type field is required.']
             ]
         ]);
        
         $this->json('post', '/users', $data->except('firstname')->toArray())
         ->assertStatus(422)
         ->assertJson([
             'message' => 'The given data was invalid.',
             'errors' => [
                 'firstname' => ['The firstname field is required unless type is in none.']
             ]
         ]);

         $this->json('post', '/users', $data->except('lastname')->toArray())
         ->assertStatus(422)
         ->assertJson([
             'message' => 'The given data was invalid.',
             'errors' => [
                 'lastname' => ['The lastname field is required unless type is in none.']
             ]
         ]);

         $this->json('post', '/users', $data->except('office')->toArray())
         ->assertStatus(422)
         ->assertJson([
             'message' => 'The given data was invalid.',
             'errors' => [
                 'office' => ['The office field is required unless type is in none.']
             ]
         ]);

         $this->json('post', '/users', $data->except('birthdate')->toArray())
         ->assertStatus(422)
         ->assertJson([
             'message' => 'The given data was invalid.',
             'errors' => [
                 'birthdate' => ['The birthdate field is required unless type is in none.']
             ]
         ]);
     }

    /** @test */
    public function edit_response_success_test()
    {
        $this->createSystemAdmin();
        
        $user = factory(User::class)->create();

        $this->get('/users' . '/' . $user->id . '/edit')
            ->assertStatus(200);
    }
    
    /** @test */
    public function edit_as_admin_response_permissions_error_test()
    {
        $this->createAdmin();

        $user = factory(User::class)->create();

        $this->get('/users' . '/' . $user->id . '/edit')
            ->assertRedirect('/403');
    }

    /** @test */
    public function edit_as_guest_response_permissions_error_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $this->get('/users' . '/' . $user->id . '/edit')
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

        $this->json('put', '/users' . '/' . $user->id, $data)
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

        $data = [];

        $this->json('put', '/users' . '/' . $user->id, $data)
            ->assertStatus(403);
    }

    /** @test */
    public function update_as_guest_response_permission_error_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $data = [];

        $this->json('put', '/users' . '/' . $user->id, $data)
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

        $this->json('delete', '/users' . '/' . $user->id)
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

        $this->json('delete', '/users' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function destroy_as_guest_response_permission_error_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $this->json('delete', '/users' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function activate_response_success_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create(['active' => false]);

        $this->json('get', '/users/activate' . '/' . $user->id)
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

        $this->json('get', '/users/activate' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function activate_as_guest_response_permission_error_test()
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $this->json('get', '/users/activate' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function resetPassword_response_success_test() 
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();

        $response = $this->json('get', '/users/password/reset' . '/' . $user->id)
            ->assertStatus(200)->decodeResponseJson();

        $this->assertNotEmpty($response);
    }

    /** @test */
    public function resetPassword_as_admin_response_permission_error_test() 
    {
        $this->createAdmin();

        $user = factory(User::class)->create();

        $this->json('get', '/users/password/reset' . '/' . $user->id)
            ->assertStatus(403);
    }

    /** @test */
    public function resetPassword_as_guest_response_permission_error_test() 
    {
        $this->createUser();

        $user = factory(User::class)->create();

        $this->json('get', '/users/password/reset' . '/' . $user->id)
            ->assertStatus(403);
    }
}
