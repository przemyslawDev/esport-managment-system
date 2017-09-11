<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;

class RoleController extends Controller
{
    public function getAll()
    {
        $roles = Role::all();

        return response()->json($roles);
    }
}
