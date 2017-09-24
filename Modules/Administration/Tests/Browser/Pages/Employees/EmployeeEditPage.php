<?php

namespace Modules\Administration\Tests\Browser\Pages\Employees;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;
use Modules\Administration\Models\Employee;

class EmployeeEditPage extends BasePage
{
    protected $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/administration/employees' . '/'. $this->employee->id . '/edit';
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
            ->assertSee('Edit Employee')
            ->assertInputValue('firstname', $this->employee->firstname)
            ->assertInputValue('lastname', $this->employee->lastname)
            ->assertInputValue('office', $this->employee->office);
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
