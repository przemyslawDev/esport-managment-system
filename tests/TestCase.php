<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createUser() 
    {
        $user = factory(User::class)->create([
            'email' => 'usertest@example.com',
            'password' => bcrypt('test')
        ]);

        $this->actingAs($user);

        return $this;
    }
}
