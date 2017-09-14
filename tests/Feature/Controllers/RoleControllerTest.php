<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleControllerTest extends TestCase 
{   
    use RefreshDatabase;

    /** @test */
    public function getAll_response_success_test() 
    {
        $this->createSystemAdmin();

        $count = Role::count();

        $response = $this->json('get', '/roles/get/all')
            ->assertStatus(200)->decodeResponseJson();

        $response_count = count($response);
        $this->assertEquals($count, $response_count);
    }

    /** @test */
    public function getAll_as_admin_response_permission_error_test() 
    {
        $this->createAdmin();
        
        $this->json('get', '/roles/get/all')
            ->assertStatus(403);
    }

    /** @test */
    public function getAll_as_guest_response_permission_error_test()
    {   
        $this->json('get', '/roles/get/all')
            ->assertStatus(401);
    }
}