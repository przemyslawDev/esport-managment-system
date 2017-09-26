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

        $this->get('/users')->assertSuccessful()->assertViewIs('users.index');
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
        $this->get('/users')->assertRedirect('/');
    }

    /** @test */
    public function getAll_response_success_test() 
    {
        $this->createSystemAdmin();

        $count = User::count() - 1;

        $response = $this->json('get', '/users/get/all')
            ->assertSuccessful()->decodeResponseJson();

        $this->assertEquals($count, $response['total']);
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
        $this->json('get', '/users/get/all')->assertStatus(401);
    }

    /** @test */
    public function create_response_success_test()
    {
        $this->createSystemAdmin();

        $this->get('users/create')->assertSuccessful()->assertViewIs('users.create');
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
        $this->get('users/create')->assertRedirect('/');
    }

    /** @test */
    public function show_response_success_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();
    
        $this->get('/users' . '/' . $user->id)->assertSuccessful()
            ->assertViewIs('users.show')->assertViewHas('id', $user->id);
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
        $user = factory(User::class)->create();

        $this->get('/users' . '/' . $user->id)->assertRedirect('/');
    }

    /** @test */
    public function get_response_success_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();

        $response = $this->json('get', '/users/user' . '/' . $user->id)
            ->assertSuccessful()->decodeResponseJson();

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
        $user = factory(User::class)->create();

        $this->json('get', '/users/user' . '/' . $user->id)
            ->assertStatus(401);
    }

    /** @test */
    public function store_user_response_success_test()
    {
        $this->createSystemAdmin();

        $role = Role::first();

        $data = [
            'email' => 'test@example.com',
            'password' => 'test123',
            'roles' => [$role->id],
            'type' => 'none'
        ];

        $response = $this->json('post', '/users', $data)->assertSuccessful()
            ->decodeResponseJson();

        $this->assertDatabaseHas('users', [
            'email' => $data['email']
        ]);

        foreach($data['roles'] as $role_id) {
            $this->assertDatabaseHas('role_user', [
                'user_id' => $response['id'],
                'role_id' => $role_id
            ]);
        }
    }

    /** @test */
    public function store_user_employee_response_success_test()
    {
        $this->createSystemAdmin();

        $role = Role::first();

        $data = [
            'email' => 'test@example.com',
            'password' => 'test123',
            'roles' => [$role->id],
            'type' => 'employee',
            'firstname' => 'test',
            'lastname' => 'test',
            'office' => 'test',
            'birthdate' => Carbon::now()->subYears(20)->toDateString()
        ];

        $response = $this->json('post', '/users', $data)->assertSuccessful()
            ->decodeResponseJson();

        $this->assertDatabaseHas('users', [
            'email' => $data['email']
        ]);

        foreach($data['roles'] as $role_id) {
            $this->assertDatabaseHas('role_user', [
                'user_id' => $response['id'],
                'role_id' => $role_id
            ]);
        }

        $this->assertDatabaseHas('employees', [
            'user_id' => $response['id'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'office' => $data['office'],
            'birthdate' => $data['birthdate']
        ]);
    }

    /** @test */
    public function store_user_manager_response_success_test()
    {
        $this->createSystemAdmin();

        $manager_role = Role::where('name', 'manager')->first();

        $data = [
            'email' => 'test@example.com',
            'password' => 'test123',
            'roles' => [$manager_role->id],
            'type' => 'employee',
            'firstname' => 'test',
            'lastname' => 'test',
            'office' => 'test',
            'birthdate' => Carbon::now()->subYears(20)->toDateString(),
            'nickname' => 'test'
        ];

        $response = $this->json('post', '/users', $data)->assertSuccessful()
        ->decodeResponseJson();

        $this->assertDatabaseHas('users', [
            'email' => $data['email']
        ]);

        foreach($data['roles'] as $role_id) {
            $this->assertDatabaseHas('role_user', [
                'user_id' => $response['id'],
                'role_id' => $role_id
            ]);
        }

        $this->assertDatabaseHas('employees', [
            'user_id' => $response['id'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'office' => $data['office'],
            'birthdate' => $data['birthdate']
        ]);

        $this->assertDatabaseHas('managers', [
            'employee_id' => $response['employee']['id'],
            'nickname' => 'test'
        ]);
    }

    /** @test */
    public function store_as_admin_response_permissions_error_test()
    {
        $this->createAdmin();

        $this->json('post', '/users', [])->assertStatus(403);
    }

    /** @test */
    public function store_as_guest_response_permissions_error_test()
    {
        $this->json('post', '/users', [])->assertStatus(401);
    }

    /** @test */
    public function store_user_response_validation_fail_test() 
    {
        $this->createSystemAdmin();

        $role = Role::first();

        $data = collect([
            'email' => 'test@example.com',
            'password' => 'test123',
            'roles' => [$role->id],
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
             'roles' => [3],
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
            ->assertSuccessful()->assertViewIs('users.edit')->assertViewHas('id', $user->id);
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
        $user = factory(User::class)->create();

        $this->get('/users' . '/' . $user->id . '/edit')
            ->assertRedirect('/');
    }

    /** @test */
    public function update_response_success_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();

        $data = [
            'email' => 'test@example.com',
            'roles' => [2,3]
        ];

        $response = $this->json('put', '/users' . '/' . $user->id, $data)
            ->assertSuccessful()->decodeResponseJson();

        $this->assertDatabaseHas('users', [
            'id' => $response['id'],
            'email' => $data['email'],
            'avatar' => $user->avatar,
            'active' => false
        ]);

        foreach($data['roles'] as $role_id) {
            $this->assertDatabaseHas('role_user', [
                'user_id' => $response['id'],
                'role_id' => $role_id
            ]);
        }
    }

    /** @test */
    public function update_as_admin_response_permission_error_test()
    {
        $this->createAdmin();

        $user = factory(User::class)->create();

        $this->json('put', '/users' . '/' . $user->id, [])
            ->assertStatus(403);
    }

    /** @test */
    public function update_as_guest_response_permission_error_test()
    {
        $user = factory(User::class)->create();

        $this->json('put', '/users' . '/' . $user->id, [])
            ->assertStatus(401);
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
            ->assertSuccessful();

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
        $user = factory(User::class)->create();

        $this->json('delete', '/users' . '/' . $user->id)
            ->assertStatus(401);
    }

    /** @test */
    public function activate_response_success_test()
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create(['active' => false]);

        $this->json('get', '/users/activate' . '/' . $user->id)
            ->assertSuccessful();

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
        $user = factory(User::class)->create();

        $this->json('get', '/users/activate' . '/' . $user->id)
            ->assertStatus(401);
    }

    /** @test */
    public function resetPassword_response_success_test() 
    {
        $this->createSystemAdmin();

        $user = factory(User::class)->create();

        $response = $this->json('get', '/users/password/reset' . '/' . $user->id)
            ->assertSuccessful()->decodeResponseJson();

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
        $user = factory(User::class)->create();
        
        $this->json('get', '/users/password/reset' . '/' . $user->id)
            ->assertStatus(401);
    }
}
