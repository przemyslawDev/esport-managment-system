<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\DashboardPage;
use Tests\Browser\Pages\Users\UsersPage;
use Tests\Browser\Pages\Users\UserViewPage;
use Tests\Browser\Pages\Users\UserCreatePage;
use Tests\Browser\Pages\Users\UserEditPage;

class UsersTest extends DuskTestCase
{
    use RefreshDatabase;

    public function test_users_view_create_edit_delete()
    {
        $user = User::where('email', 'admin@example.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(new DashboardPage)
                ->clickLink('Users')
                ->on(new UsersPage)
                ->waitForLink('Create')
                ->clickLink('Create')
                ->on(new UserCreatePage)
                ->waitFor('form')
                ->select('type', 'none')
                ->type('email', 'test@example.com')
                ->type('password', 'secret')
                ->waitFor('select[name=roles]')
                ->select('roles', '2')
                ->script('window.scrollTo(0, 150);');
            
            $browser->press('Submit')
                ->waitFor('.alert')
                ->assertSee('Data created.')
                ->clickLink('Users')
                ->on(new UsersPage)
                ->assertSee('Users');

            $created_user = User::where('email', 'test@example.com')->first();
            $browser->waitFor('table')
                ->with('table', function ($table) use ($created_user) {
                    $table->assertSee($created_user->email)
                        ->click('a[href="users/' . $created_user->id . '/edit"]');
                });
            
            $browser->on(new UserEditPage($created_user))
                ->type('email', 'test_edit@example.com')
                ->press('Submit')
                ->waitFor('.alert')
                ->assertSee('Data updated.')
                ->clickLink('Users')
                ->on(new UsersPage)
                ->assertSee('Users');
            
            $updated_user = User::where('email', 'test_edit@example.com')->first();
            $browser->waitFor('table')
                ->with('table', function ($table) use ($updated_user) {
                    $row = $this->getUserRow($table, $updated_user);
                    $row->findElement(WebDriverBy::linkText('Activate'))->click();
                });

            $browser->waitFor('.alert')
                ->assertSee('The user has been activated.');

            $browser->clickLink('Logout')
                ->visit('/account/verify' . '/' . $updated_user->confirmation_code)
                ->on(new DashboardPage)
                ->clickLink('Logout')

                ->on(new LoginPage)
                ->assertSee('Please Sign In')
                ->type('email', $user->email)
                ->type('password', 'secret')
                ->press('Login')
                ->on(new DashboardPage)
                ->clickLink('Users')
                ->on(new UsersPage)
                ->assertSee('Users');


            $browser->waitFor('table')
                ->with('table', function ($table) use ($updated_user) {
                    $row = $this->getUserRow($table, $updated_user);
                    $row->findElement(WebDriverBy::linkText('Deactivate'))->click();
                });
            
            $browser->waitFor('.alert')
                ->assertSee('The user has been deactivated.');

            $browser->with('table', function ($table) use ($updated_user) {
                    $table->assertSee($updated_user->email)
                        ->click('a[href="users/' . $updated_user->id . '"]');
            });

            $browser->on(new UserViewPage($updated_user))
                ->clickLink('Users')
                ->on(new UsersPage)
                ->assertSee('Users');

            $browser->waitFor('table')
                ->with('table', function ($table) use ($updated_user) {
                    $row = $this->getUserRow($table, $updated_user);
                    $row->findElement(WebDriverBy::linkText('Delete'))->click();
                });
            
            $browser->waitFor('.alert')
                ->assertSee('Data deleted.')
                ->assertDontSee($updated_user->email);
        });
    }

    /**
    * Get row where is the user
    * @return \Facebook\WebDriver\Remote\RemoteWebElement|null
    */
    private function getUserRow($table, User $user)
    {
        $table->assertSee($user->email);
        $rows = $table->elements('tbody tr');
        foreach ($rows as $row) {
            if (strpos($row->getText(), $user->email) !== false) {
                return $row;
            }
        }
        return null;
    }
}
