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

        $this->refreshRoles($user, $array);
    
        if(!$update) {
            Mail::to($user->email)->send(new UserVerification($user));
            if($array['type'] !== 'none') {
                $employee = $this->saveEmployee($user, $array);
                $this->saveExpandendUser($employee, $array);
            }
        }

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

    private function saveEmployee(User $user, array $array) 
    {
        return $user->employee()->create([
            'firstname' => $array['firstname'],
            'lastname' => $array['lastname'],
            'office' => $array['office'],
            'birthdate' => $array['birthdate']
        ]);
    }

    private function saveExpandendUser(Employee $employee, array $array)
    {
        foreach($array['roles'] as $role_id) {
            $role = Role::find($role_id);

            switch ($role->name) {
                case 'manager': 
                    $employee->manager()->create([
                        'nickname' => $array['nickname']
                    ]);

                    $manager = $employee->manager()->first();
                    $manager->games()->attach($array['manager_games']);
                default: 
                    //
            }
        }
    }
}