<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\DashboardPage;
use App\User;

class LoginTest extends DuskTestCase
{
    use RefreshDatabase;
    
    public function testEnter_successs()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->assertSee('Please Sign In');
        });
    }

    public function testLogin_fail()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->assertSee('Please Sign In')
                    ->type('email', str_random(10) . '@example.com')
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertSee('These credentials do not match our records.');
        });
    }
    
    public function testLogin_success()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->assertSee('Please Sign In')
                    ->type('email', 'admin@example.com')
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertPathIs('/dashboard')
                    ->on(new DashboardPage);
        });
    }
}
