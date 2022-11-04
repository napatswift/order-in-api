<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Http\Resources\EmployeeResource;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    public function index()
    {
        return Employee::all();
    }

    public function show(Employee $employee)
    {
        return new EmployeeResource($employee);
    }

    public function store(StoreEmployeeRequest $request)
    {
        $employee = new EmployeeResource(Employee::create($request->all()));
        if ($employee->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Employee saved successfully',
                'employees_id' => $employee->id
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Employee saved failed'
        ], 500);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->all());
        if ($employee->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully',
                'employees_id' => $employee->id
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Employee updated failed'
        ], 500);
    }

    public function destroy(Employee $employee)
    {
        $name = $employee->name;
        if ($employee->delete()) {
            return response()->json([
                'success' => true,
                'message' => "Employee {$name} deleted successfully"
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => "Employee {$name} deleted failed"
        ], 500);
    }
}
