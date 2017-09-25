<?php

namespace Modules\Teammanagment\Tests\Browser\Pages\Teams;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;
use Modules\Teammanagment\Models\Team;

class TeamEditPage extends BasePage
{
    protected $team;
    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/teammanagment/teams' . '/' . $this->team->id . '/edit';
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
            ->waitFor('form')
            ->assertSee('Edit Team')
            ->assertInputValue('name', $this->team->name)
            ->assertInputValue('tag', $this->team->tag);
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
