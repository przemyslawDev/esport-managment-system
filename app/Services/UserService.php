<?php

namespace App\Services;

use Mail;
use App\User;
use App\Role;
use App\Mail\UserVerification;
use Modules\Administration\Models\Employee;

class UserService 
{
    public function save(array $array)
    {        
        $update = array_key_exists('id', $array);
        if(!$update) {
            $user = new User();
            $user->password = bcrypt($array['password']);
            $user->confirmation_code = str_random(30);
        } else {
            $user = User::findOrFail($array['id']);
        }
        $user->email = $array['email'];
        $user->save();
    
        if(!$update) {
            Mail::to($user->email)->send(new UserVerification($user));
            if($array['type'] !== 'none') {
                $employee = $this->createEmployee($user, $array);
                $this->createExpandendUser($employee, $array);
            }
        } else {
            if($user->has('employee')) {
                $employee = $user->employee()->first();
                if($user->hasRole('manager')) {
                    if(in_array(Role::where('name', 'manager')->first()->id , $array['roles'])) {
                        $this->updateManager($employee, $array);
                    } else {
                        $this->deleteManager($employee, $array);
                    }
                } else {
                    if (in_array(Role::where('name', 'manager')->first()->id, $array['roles'])) {
                        $this->createManager($employee, $array);
                    }
                }
            }
        }

        $this->refreshRoles($user, $array);

        return User::with('employee')->find($user->id);
    }

    private function refreshRoles(User $user, array $array)
    {
        foreach($array['roles'] as $role_id) {
            $role = Role::find($role_id);
            $roles[] = $role;
        }
        $user->detachRoles();
        $user->attachRoles($roles);
    }

    private function createEmployee(User $user, array $array)
    {
        return $user->employee()->create([
            'firstname' => $array['firstname'],
            'lastname' => $array['lastname'],
            'office' => $array['office'],
            'birthdate' => $array['birthdate']
        ]);
    }

    private function createExpandendUser(Employee $employee, array $array)
    {
        foreach($array['roles'] as $role_id) {
            $role = Role::find($role_id);

            switch ($role->name) {
                case 'manager':
                    $this->createManager($employee, $array);
                default: 
                    //
            }
        }
    }

    private function createManager(Employee $employee, array $array)
    {
        $employee->manager()->create([
            'nickname' => $array['nickname']
        ]);

        $manager = $employee->manager()->first();
        $manager->games()->attach($array['manager_games']);
    }

    private function updateManager(Employee $employee, array $array)
    {
        $manager = $employee->manager()->first();
        $manager->nickname = $array['nickname'];
        $manager->games()->detach();
        $manager->games()->attach($array['manager_games']);
    }

    private function deleteManager(Employee $employee, array $array)
    {
        $employee->manager()->delete();
    }
}