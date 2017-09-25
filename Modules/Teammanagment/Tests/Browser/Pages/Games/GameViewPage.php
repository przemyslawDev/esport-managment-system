<?php

namespace Modules\Teammanagment\Tests\Browser\Pages\Games;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;
use Modules\Teammanagment\Models\Game;

class GameViewPage extends BasePage
{
    protected $game;
    public function __construct(Game $game) 
    {
        $this->game = $game;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/teammanagment/games' . '/' . $this->game->id;
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())
            ->waitFor('table')
            ->assertSee($this->game->name);
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }
}
