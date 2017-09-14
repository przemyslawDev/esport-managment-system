<?php

namespace App\Services;

use App\User;
use App\Role;

class UserService 
{
    public function save(array $array)
    {        
        $update = array_key_exists('id', $array);
        if(!$update) {
            $user = new User();
            $user->password = bcrypt($array['password']);
        } else {
            $user = User::findOrFail($array['id']);
        }
        $user->email = $array['email'];
        $user->save();

        $this->refreshRoles($user, $array);
    
        if(!$update && $array['type'] !== 'none') {
            $this->saveEmployee($user, $array);
        }

        return $user;
    }

    private function refreshRoles(User $user, array $array)
    {
        foreach($array['roles'] as $role_id) {
            $role = Role::where('id', $role_id)->first();
            $roles[] = $role;
        }
        $user->detachRoles();
        $user->attachRoles($roles);
    }

    private function saveEmployee(User $user, array $array) 
    {
        $user->employee()->create([
            'firstname' => $array['firstname'],
            'lastname' => $array['lastname'],
            'office' => $array['office'],
            'birthdate' => $array['birthdate'],
            'status' => $array['status']
        ]);
    }
}