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

        $response = $this->json('get', '/roles/get/all')
            ->assertStatus(200);
    }

    /** @test */
    public function getAll_as_admin_response_permission_error_test() 
    {
        $this->createAdmin();
        
        $response = $this->json('get', '/roles/get/all')
            ->assertStatus(403);
    }

    /** @test */
    public function getAll_as_guest_response_permission_error_test()
    {
        $this->createUser();
        
        $response = $this->json('get', '/roles/get/all')
            ->assertStatus(403);
    }
}