<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
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
        $users = User::all();

        return response()->json($users);
    }

    public function show($id)
    {
        return view('users.show')->with($id);
    }

    public function get($id)
    {
        $user = User::findOrFail($id);

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

        return response()->json($user);
    }

    public function edit($id)
    {
        return view('users.edit')->with('id', $id);
    }

    public function update($id, UpdateUserRequest $request)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        return response()->json($user);
    }

    public function destroy($id)
    {
        User::destroy($id);
    }
}
