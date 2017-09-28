<?php

namespace Modules\Teammanagment\Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\Teammanagment\Models\Team;
use Modules\Teammanagment\Models\Game;
use Modules\Teammanagment\Tests\Browser\Pages\Teams\TeamsPage;
use Modules\Teammanagment\Tests\Browser\Pages\Teams\TeamViewPage;
use Modules\Teammanagment\Tests\Browser\Pages\Teams\TeamCreatePage;
use Modules\Teammanagment\Tests\Browser\Pages\Teams\TeamEditPage;

class TeamsTest extends DuskTestCase
{
    use RefreshDatabase;

    public function test_team_create()
    {
        $user = User::where('email', 'admin@example.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(new TeamCreatePage)
                    ->waitFor('form')
                    ->type('name', 'test')
                    ->type('tag', 'test')
                    ->waitFor('select[name=games]')
                    ->select('games', '2')
                    ->press('Submit')
                    ->waitFor('.alert')
                    ->assertSee('Data created.');
        });
    }
    
    public function test_teams_view()
    {
        $user = User::where('email', 'admin@example.com')->first();
        
        $this->browse(function (Browser $browser) use ($user) {

            $team = Team::first();

            $browser->loginAs($user)
                    ->visit(new TeamsPage)
                    ->assertSee('Teams');

            $browser->waitFor('table')->with('table', function ($table) use ($team){
                $table->assertSee($team->name)
                ->click('a[href="teams/' . $team->id . '"]');
            });

            $browser->on(new TeamViewPage($team));
        });
    }

    public function test_team_edit()
    {
        $user = User::where('email', 'admin@example.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            
            $team = Team::first();
            
            $browser->loginAs($user)
                    ->visit(new TeamEditPage($team))
                    ->type('name', 'test2')
                    ->type('tag', 'test2')
                    ->press('Submit')
                    ->waitFor('.alert')
                    ->assertSee('Data updated.');
        });
    }

    public function test_team_attach_detach_game()
    {
        $user = User::where('email', 'admin@example.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $team = Team::first();
            
            $browser->loginAs($user)
                ->visit(new TeamsPage)
                ->assertSee('Teams');

            $browser->waitFor('table')->with('table', function($table) use ($team) {
                $table->script('window.scrollTo(0, 100);');

                $row = $this->getTeamRow($table, $team);
                $row->findElement(WebDriverBy::className('btn-circle'))->click();

                $table->waitFor('select');
               
                $table->select('game');
            });

            $browser->waitFor('.alert')
                ->assertSee('Game attached.')
                ->waitFor('table')->with('table', function($table) use ($team) {
                    $table->script('window.scrollTo(0, 100);');
                    $row = $this->getTeamRow($table, $team);
                    $row->findElement(WebDriverBy::className('btn-game'))->click();
                });

            $browser->waitFor('.alert')
                ->assertSee('Game detached.')
                ->waitFor('table')->with('table', function($table) use ($team) {
                    $table->script('window.scrollTo(0, 100);');
                    $row = $this->getTeamRow($table, $team);
                    
                    $game_button = $row->findElements(WebDriverBy::tagName('button'));

                    $this->assertEquals(2, count($game_button));
                });
        });
    }

    /*public function test_team_attach_detach_manager_as_system_admin()
    {
        $user = User::where('email', 'admin@example.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $team = Team::first();

            $browser->loginAs($user)
                ->visit(new TeamViewPage($team))
                ->click('button[]Attach Manager');

            $browser->waitFor('table')->with('table', function ($table) use ($team) {
                $table->script('window.scrollTo(0,100);');

                $row = $this->getManagerRowByNickname($table, 'manager');
                $row_selector = $row->findElement(WebDriverBy::tagName('tr'))->click();
            });

            $browser->waitFor('.alert')
                ->assertSee('You attach manager.');

            $browser->clickLink('Detach Manager')
                ->waitFor('.alert')
                ->assertSee('You detach this team.');
        });
    }

    public function test_team_attach_detach_as_manager()
    {
        $user = User::where('email', 'manager@example.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $team = Team::first();

            $browser->loginAs($user)
                ->visit(new TeamsPage)
                ->assertSee('Teams');

            $browser->waitFor('table')->with('table', function ($table) use ($team) {
                $table->script('window.scrollTo(0,100);');

                $row = $this->getTeamRow($table, $team);
            });
        });
    }*/

    public function test_team_delete()
    {
        $user = User::where('email', 'admin@example.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $team = Team::first();

            $browser->loginAs($user)
                ->visit(new TeamsPage)
                ->assertSee('Teams');

            $browser->waitFor('table')->with('table', function($table) use ($team){
                $row = $this->getTeamRow($table, $team);
                $row->findElement(WebDriverBy::linkText('Delete'))->click();
            });

            $browser->waitFor('.alert')
                ->assertSee('Data deleted.')
                ->assertDontSee($team->name);
        });
    }

    /**
    * Get row where is the team
    * @return \Facebook\WebDriver\Remote\RemoteWebElement|null
    */
    private function getTeamRow($table, Team $team)
    {
        $table->assertSee($team->name);
        $rows = $table->elements('tbody tr');
        foreach ($rows as $row) {
            if (strpos($row->getText(), $team->name) !== false) {
                return $row;
            }
        }
        return null;
    }

    /**
     * Get row where is the manager
     * @return \Facebook\WebDriver\Remote\RemoteWebElement|null
     */
    private function getManagerRowByNickname($table, $nickname)
    {
        $table->assertSee($nickname);
        $rows = $table->elements('tbody tr');
        foreach ($rows as $row) {
            if (strpos($row->getText(), $nickname) !== false) {
                return $row;
            }
        }
        return null;
    }
}
