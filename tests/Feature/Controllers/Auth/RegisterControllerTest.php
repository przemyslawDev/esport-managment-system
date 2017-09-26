<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;
use App\User;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function confirm_response_success_test()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('test123'),
            'active' => 1,
            'confirmation_code' => str_random(30)
        ]);

        $this->get('/account/verify' . '/' . $user->confirmation_code)
            ->assertRedirect('/dashboard');

        $this->assertTrue(User::where('id', $user->id)->first()->confirmed);
    }

    /** @test */
    public function confirm_response_empty_code_fail_test()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('test123'),
            'active' => 1
        ]);

        $this->get('/account/verify' . '/' . ' ')
            ->assertRedirect('/403');

        $this->assertFalse(User::where('id', $user->id)->first()->confirmed);
    }

    /** @test */
    public function confirm_response_user_not_found_fail_test()
    {
        $code = str_random(30);

        $this->get('/account/verify' . '/' . $code)->assertRedirect('/403');
    }
}