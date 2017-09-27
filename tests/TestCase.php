<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\User;
use App\Role;
use Modules\Administration\Models\Employee;
use Modules\Teammanagment\Models\Manager;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createManager()
    {
        $user = factory(User::class)->create([
            'email' => 'managertest@example.com',
            'password' => bcrypt('test'),
            'active' => true,
            'confirmed' => true
        ]);

        $role = Role::where('name', 'manager')->first();
        
        $user->attachRole($role);

        $this->actingAs($user);

        $manager = factory(Manager::class)->create();

        $employee = Employee::find($manager->employee_id);

        $user->employee()->save($employee);

        return $user;
    }

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
