<?php

namespace Modules\Administration\Tests\Feature\Controllers;

use Tests\TestCase;
use Modules\Administration\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_response_success_test()
    {
        $this->createAdmin();

        $this->get('/administration/employees')->assertStatus(200);
    }

    /** @test */
    public function index_as_guest_reponse_permission_error_test()
    {
        $this->get('/administration/employees')->assertRedirect('/403');
    }

    /** @test */
    public function getAll_response_success_test()
    {
        $this->createAdmin();

        $count = Employee::count();

        $response = $this->json('get', '/administration/employees/get/all')
            ->assertStatus(200)
            ->decodeResponseJson();

        $response_count = count($response);
        $this->assertEquals($count, $response_count);
    }

    /** @test */
    public function getAll_as_guest_response_permission_error_test()
    {
        $this->json('get', '/administration/employees/get/all')
            ->assertStatus(403);
    }

    /** @test */
    public function create_response_success_test()
    {
        $this->createAdmin();

        $this->get('/administration/employees/create')->assertStatus(200);
    }

    /** @test */
    public function create_as_guest_response_permission_error_test()
    {
        $this->get('/administration/employees/create')->assertRedirect('/403');
    }

    /** @test */
    public function show_response_success_test()
    {
        $auth = $this->createAdmin();

        $employee = factory(Employee::class)
            ->create(['user_id' => $auth->id]);

        $this->get('/administration/employees/' . $employee->id)
            ->assertStatus(200);
    }

    /** @test */
    public function show_as_guest_response_permission_error_test()
    {
        $employee = factory(Employee::class)->create();

        $this->get('/administration/employees/' . $employee->id)
            ->assertRedirect('/403');
    }

    /** @test */
    public function get_response_success_test()
    {
        $this->createAdmin();

        $employee = factory(Employee::class)->create();

        $response = $this->json('get', '/administration/employees/employee/' . $employee->id)
            ->assertStatus(200)->decodeResponseJson();

        $this->assertNotEmpty($response);
    }

    /** @test */
    public function get_as_guest_response_permission_error_test()
    {
        $employee = factory(Employee::class)->create();
        
        $this->json('get', '/administration/employees/employee/' . $employee->id)
            ->assertStatus(403);
    }

    /** @test */
    public function store_response_success_test()
    {
        $this->createAdmin();

        $data = [
            'firstname' => 'test',
            'lastname' => 'test',
            'office' => 'test',
            'birthdate' => Carbon::now()->subYears(20)->toDateString()
        ];

        $this->json('post', '/administration/employees', $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('employees', $data);
    }

    /** @test */
    public function store_as_guest_response_permission_error_test()
    {
        $data= [];

        $this->json('post', '/administration/employees', $data)
            ->assertStatus(403);
    }

    /** @test */
    public function store_response_validation_fail_test()
    {
        $this->createAdmin();
        
        $data = collect([
            'firstname' => 'test',
            'lastname' => 'test',
            'office' => 'test',
            'birthdate' => Carbon::now()->subYears(20)->toDateString()
        ]);

        $this->json('post', '/administration/employees', $data->except('firstname')->toArray())
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'firstname' => ['The firstname field is required.']
                ]
            ]);

        $this->json('post', '/administration/employees', $data->except('lastname')->toArray())
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'lastname' => ['The lastname field is required.']
            ]
        ]);

        $this->json('post', '/administration/employees', $data->except('office')->toArray())
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'office' => ['The office field is required.']
            ]
        ]);
        
        $this->json('post', '/administration/employees', $data->except('birthdate')->toArray())
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'birthdate' => ['The birthdate field is required.']
            ]
        ]);
    }

    /** @test */
    public function edit_response_success_test()
    {
        $this->createAdmin();

        $employee = factory(Employee::class)->create();

        $this->get('/administration/employees' . '/' . $employee->id . '/edit')
            ->assertStatus(200);
    }

    /** @test */
    public function edit_as_guest_response_permission_error_test()
    {
        $employee = factory(Employee::class)->create();
        
        $this->get('/administration/employees' . '/' . $employee->id . '/edit')
            ->assertRedirect('/403');
    }

    /** @test */
    public function update_response_success_test()
    {
        $this->createAdmin();

        $employee = factory(Employee::class)->create();
        
        $data = [
            'firstname' => 'test',
            'lastname' => 'test',
            'office' => 'test',
            'birthdate' => Carbon::today()->subYears(20)->toDateString()
        ];

        $this->json('put', '/administration/employees' . '/' . $employee->id, $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('employees', $data);
    }

    /** @test */
    public function update_as_guest_response_permission_error_test()
    {        
        $employee = factory(Employee::class)->create();

        $data = [];

        $this->json('put', '/administration/employees' . '/' . $employee->id, $data)
            ->assertStatus(403);
    }

    /** @test */
    public function update_response_vailidation_fail_test()
    {
        $this->createAdmin();
        
        $employee = factory(Employee::class)->create();

        $data = collect([
            'firstname' => 'test',
            'lastname' => 'test',
            'office' => 'test',
            'birthdate' => Carbon::now()->subYears(20)->toDateString()
        ]);

        $this->json('put', '/administration/employees' . '/' . $employee->id, $data->except('firstname')->toArray())
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'firstname' => ['The firstname field is required.']
                ]
            ]);

        $this->json('put', '/administration/employees' . '/' . $employee->id, $data->except('lastname')->toArray())
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'lastname' => ['The lastname field is required.']
            ]
        ]);

        $this->json('put', '/administration/employees' . '/' . $employee->id, $data->except('office')->toArray())
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'office' => ['The office field is required.']
            ]
        ]);

        $this->json('put', '/administration/employees' . '/' . $employee->id, $data->except('birthdate')->toArray())
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'birthdate' => ['The birthdate field is required.']
            ]
        ]);
    }   

    /** @test */
    public function destroy_response_success_test()
    {
        $this->createAdmin();

        $employee = factory(Employee::class)->create();

        $this->json('delete', '/administration/employees' . '/' . $employee->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
            'firstname' => $employee->firstname,
            'lastname' => $employee->lastname 
        ]);
    }

    /** @test */
    public function destroy_as_guest_response_permission_error_test()
    {
        $employee = factory(Employee::class)->create();
        
        $this->json('delete', '/administration/employees' . '/' . $employee->id)
            ->assertStatus(403);
    }
}