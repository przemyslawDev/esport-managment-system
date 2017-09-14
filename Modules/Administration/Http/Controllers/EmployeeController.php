<?php

namespace Modules\Administration\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Administration\Models\Employee;
use Modules\Administration\Http\Requests\StoreEmployeeRequest;
use Modules\Administration\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('administration::employees.index');
    }

    public function getAll()
    {
        $employees = Employee::with('user')->get();

        return response()->json($employees);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('administration::employees.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(StoreEmployeeRequest $request)
    {
        $employee = new Employee();
        $employee->firstname = $request->input('firstname');
        $employee->lastname = $request->input('lastname');
        $employee->office = $request->input('office');
        $employee->birthdate = $request->input('birthdate');
        $employee->status = $request->input('status');
        $employee->save();

        return response()->json($employee);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        return view('administration::employees.show')->with('id', $id);
    }

    public function get($id)
    {
        $employee = Employee::find($id)->with('user')->first();

        return response()->json($employee);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        return view('administration::employees.edit')->with('id', $id);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, UpdateEmployeeRequest $request)
    {
        $employee = Employee::findOrFail($id);
        $employee->firstname = $request->input('firstname');
        $employee->lastname = $request->input('lastname');
        $employee->office = $request->input('office');
        $employee->birthdate = $request->input('birthdate');
        $employee->status = $request->input('status');
        $employee->save();

        return response()->json($employee);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        Employee::destroy($id);
    }
}
