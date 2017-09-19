<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Tests\Browser\Pages\DashboardPage;

class DashboardTest extends DuskTestCase
{
    use RefreshDatabase;
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
                    ->visit(new DashboardPage);
        });
    }
}
