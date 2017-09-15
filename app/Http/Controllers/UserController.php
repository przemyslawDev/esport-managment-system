<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function getAll()
    {
        $users = User::with('roles')->paginate(1);

        return response()->json($users);
    }

    public function create()
    {
        return view('users.create');
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

    public function store(StoreUserRequest $request, UserService $service)
    {
        $user = $service->save($request->all());

        return response()->json($user);
    }

    public function edit($id)
    {
        return view('users.edit')->with('id', $id);
    }

    public function update($id, UpdateUserRequest $request, UserService $service)
    {
        $array = $request->all();
        $array['id'] = $id;

        $user = $service->save($array);

        return response()->json($user);
    }

    public function activate($id) 
    {
        $user = User::findOrFail($id);
        $user->active = ($user->active ? false : true);
        $user->save();

        return response()->json($user);
    }

    public function resetPassword($id)
    {
        $random_password = str_random(10);

        $user = User::findOrFail($id);
        $user->password = bcrypt($random_password);
        $user->save();

        return response()->json($random_password);
    }   

    public function destroy($id)
    {
        User::destroy($id);
    }
}
