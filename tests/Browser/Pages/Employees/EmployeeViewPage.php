<?php

namespace Tests\Browser\Pages\Employees;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;
use Modules\Administration\Models\Employee;

class EmployeeViewPage extends BasePage
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
        return '/administration/employees' . '/' . $this->employee->id;
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
            ->assertSee($this->employee->firstname);  
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
