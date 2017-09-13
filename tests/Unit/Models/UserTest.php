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
        $user = factory(User::class)
            ->create()
            ->each(function ($u) {
                $u->employee()->save(factory(Employee::class)->create(['user_id' => $u->id]));
            });
            
        $this->assertNotEmpty($user);
    }
}