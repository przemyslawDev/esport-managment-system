<?php

namespace Modules\Teammanagment\Tests\Browser;

use Modules\Teammanagment\Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Tests\Browser\Pages\DashboardPage;
use Modules\Teammanagment\Tests\Browser\Pages\Games\GamesPage;
use Modules\Teammanagment\Tests\Browser\Pages\Games\GameViewPage;
use Modules\Teammanagment\Models\Game;

class GamesTest extends DuskTestCase
{
    public function test_games_view()
    {
        $user = User::where('email', 'admin@example.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(new DashboardPage)
                    ->clickLink('Games')
                    ->on(new GamesPage)
                    ->assertSee('Games');

            $game = Game::first();
            $browser->waitFor('table')->with('table', function($table) use ($game){
                $table->assertSee($game->name)
                ->click('a[href="games/' . $game->id . '"]');
            });

            $browser->on(new GameViewPage($game));
        });
    }
}
