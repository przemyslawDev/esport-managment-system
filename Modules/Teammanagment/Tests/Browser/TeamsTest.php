<?php

namespace Modules\Teammanagment\Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Modules\Teammanagment\Models\Team;
use Modules\Teammanagment\Models\Game;
use Modules\Teammanagment\Tests\Browser\Pages\Teams\TeamsPage;
use Modules\Teammanagment\Tests\Browser\Pages\Teams\TeamViewPage;
use Modules\Teammanagment\Tests\Browser\Pages\Teams\TeamCreatePage;
use Modules\Teammanagment\Tests\Browser\Pages\Teams\TeamEditPage;

class TeamsTest extends DuskTestCase
{
    public function test_teams_view()
    {
        $user = User::where('email', 'admin@example.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $team = factory(Team::class)->create();

            $browser->loginAs($user)
                    ->visit(new TeamsPage)
                    ->assertSee('Teams');

            $browser->waitFor('table')->with('table', function ($table) use ($team) {
                $table->assertSee($team->name)
                ->click('a[href="teams/' . $team->id . '"]');
            });

            $browser->on(new TeamViewPage($team));
        });
    }

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

    public function test_team_edit()
    {
        $user = User::where('email', 'admin@example.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            
            $team = factory(Team::class)->create();
            
            $browser->loginAs($user)
                    ->visit(new TeamEditPage($team))
                    ->type('name', 'test2')
                    ->type('tag', 'test2')
                    ->press('Submit')
                    ->waitFor('.alert')
                    ->assertSee('Data updated.');
        });
    }

    public function test_team_delete()
    {
        $user = User::where('email', 'admin@example.com')->first();

        
        $this->browse(function (Browser $browser) use ($user) {
            $team = factory(Team::class)->create();

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
}
