<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Browser\Pages\DashboardPage;
use Modules\Administration\Models\Employee;
use Tests\Browser\Pages\Employees\EmployeesPage;
use Tests\Browser\Pages\Employees\EmployeeViewPage;
use Tests\Browser\Pages\Employees\EmployeeEditPage;
use Tests\Browser\Pages\Employees\EmployeeCreatePage;
use App\User;

class EmployeesTest extends DuskTestCase
{
    use RefreshDatabase;

    public function test_employees_view_create_edit_delete()
    {
        $user = User::where('email', 'administrator@example.com')->first();

        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
                ->visit(new DashboardPage)
                ->clickLink('Employees')
                ->on(new EmployeesPage)
                ->waitFor('a[href="employees/create"]')
                ->click('a[href="employees/create"]')
                ->on(new EmployeeCreatePage)
                ->waitFor('form')
                ->type('firstname', 'test')
                ->type('lastname', 'test')
                ->type('office', 'test');

            $browser->press('Submit')
                ->waitFor('.alert')
                ->assertSee('Data created.')
                ->clickLink('Employees')
                ->on(new EmployeesPage)
                ->assertSee('Employees');
            
            $created_employee = Employee::where('firstname', 'test')->first();
            $browser->waitFor('table')
                ->with('table', function($table) use ($created_employee) {
                    $table->assertSee($created_employee->firstname)
                        ->click('a[href="employees/' . $created_employee->id . '/edit"]');
                });
            
            $browser->on(new EmployeeEditPage($created_employee))
                ->type('firstname', 'testedit')
                ->press('Submit')
                ->waitFor('.alert')
                ->assertSee('Data updated.')
                ->clickLink('Employees')
                ->on(new EmployeesPage)
                ->assertSee('Employees');

            $updated_employee = Employee::where('firstname', 'testedit')->first();
            $browser->waitFor('table')
                ->with('table', function($table) use ($updated_employee) {
                    $table->assertSee($updated_employee->firstname)
                        ->click('a[href="employees/' . $updated_employee->id . '"]');
                });
            
            $browser->on(new EmployeeViewPage($updated_employee))
            ->clickLink('Employees')
            ->on(new EmployeesPage)
            ->assertSee('Employees');

            $browser->waitFor('table')
                ->with('table', function($table) use ($updated_employee) {
                   $row = $this->getEmployeeRow($table, $updated_employee);
                   $row->findElement(WebDriverBy::linkText('Delete'))->click();
                });

            $browser->waitFor('.alert')
                ->assertSee('Data deleted.')
                ->assertDontSee($updated_employee->firstname);
        });
    }

    /**
    * Get row where is the employee
    * @return \Facebook\WebDriver\Remote\RemoteWebElement|null
    */
    private function getEmployeeRow($table, Employee $employee)
    {
        $table->assertSee($employee->firstname);
        $rows = $table->elements('tbody tr');
        foreach ($rows as $row) {
            if (strpos($row->getText(), $employee->firstname) !== false) {
                return $row;
            }
        }
        return null;
    }
}
