<?php

namespace Tests\Browser\Pages\Users;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;
use App\User;

class UserEditPage extends BasePage
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/users' . '/' . $this->user->id . '/edit';
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
            ->assertSee('Edit User')
            ->assertInputValue('email', $this->user->email);
            foreach($this->user->roles() as $role) {
                $browser->assertSelected('roles', $role->id);
            }
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
