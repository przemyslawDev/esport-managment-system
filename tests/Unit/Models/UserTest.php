<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\User;
use Modules\Administration\Models\Employee;

class UserTest extends TestCase
{   
    /** @test */
    public function employee_success_test()
    {
        $user = factory(User::class)->create();
        $employee = factory(Employee::class)->create();

        $user->employee()->save($employee);

        $this->assertNotEmpty($user->employee);
    }
}