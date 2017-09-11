<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function getAll()
    {
        $users = User::with('roles')->get();

        return response()->json($users);
    }

    public function show($id)
    {
        return view('users.show')->with('id', $id);
    }

    public function get($id)
    {
        $user = User::where('id', $id)->with('roles')->first();

        return response()->json($user);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $user = new User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $role = Role::where('name', 'guest')->first();
        $user->attachRole($role);

        return response()->json($user);
    }

    public function edit($id)
    {
        return view('users.edit')->with('id', $id);
    }

    public function update($id, UpdateUserRequest $request)
    {
        $user = User::findOrFail($id);
        $user->email = $request->input('email');
        $user->save();

        if (!empty($request->input('roles'))) {
            $roles = array();
            foreach($request->input('roles') as $role_id) {
                $role = Role::where('id', $role_id)->first();
                array_push($roles, $role);
            }  
            $user->detachRoles();
            $user->attachRoles($roles);
        }

        return response()->json($user);
    }

    public function activate($id) 
    {
        $user = User::findOrFail($id);
        $user->active = ($user->active ? false : true);
        $user->save();

        return response()->json($user);
    }

    public function destroy($id)
    {
        User::destroy($id);
    }
}
