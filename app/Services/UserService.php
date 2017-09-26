<?php

namespace App\Services;

use App\User;
use App\Role;
use App\Mail\UserVerification;
use Mail;

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
                $this->saveEmployee($user, $array);
            }
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
            'birthdate' => $array['birthdate']
        ]);
    }
}