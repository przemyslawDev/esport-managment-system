<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class DashboardTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testEnter_success()
    {
        $user = User::where('active', true)->first();

        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->assertSee('Dashboard');
        });
    }
}
