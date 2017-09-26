<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\User;
use App\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createAdmin() 
    {
        $user = factory(User::class)->create([
            'email' => 'admintest@example.com',
            'password' => bcrypt('test'),
            'active' => true,
            'confirmed' => true
        ]);

        $role = Role::where('name', 'admin')->first();
        
        $user->attachRole($role);

        $this->actingAs($user);

        return $user;
    }

    protected function createSystemAdmin()
    {
        $user = factory(User::class)->create([
            'email' => 'systemadmintest@example.com',
            'password' => bcrypt('test'),
            'active' => true,
            'confirmed' => true
        ]);

        $role = Role::where('name', 'system_admin')->first();
        
        $user->attachRole($role);

        $this->actingAs($user);

        return $user;
    }
}
