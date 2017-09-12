<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\User;
use App\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createUser() 
    {
        $user = factory(User::class)->create([
            'email' => 'usertest@example.com',
            'password' => bcrypt('test'),
            'active' => true
        ]);

        $role = Role::where('name', 'guest')->first();
        
        $user->attachRole($role);

        $this->actingAs($user);

        return $this;
    }

    protected function createAdmin() 
    {
        $user = factory(User::class)->create([
            'email' => 'admintest@example.com',
            'password' => bcrypt('test'),
            'active' => true
        ]);

        $role = Role::where('name', 'admin')->first();
        
        $user->attachRole($role);

        $this->actingAs($user);

        return $this;
    }

    protected function createSystemAdmin()
    {
        $user = factory(User::class)->create([
            'email' => 'systemadmintest@example.com',
            'password' => bcrypt('test'),
            'active' => true
        ]);

        $role = Role::where('name', 'system_admin')->first();
        
        $user->attachRole($role);

        $this->actingAs($user);

        return $this;
    }
}
