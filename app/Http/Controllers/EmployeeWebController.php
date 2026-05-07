<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeWebController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        
        // Calculate statistics for dashboard
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('is_active', true)->count();
        $veteranEmployees = Employee::where('is_active', true)
            ->whereRaw('TIMESTAMPDIFF(YEAR, joining_date, CURDATE()) > 5')
            ->count();
        $departmentsCount = Employee::distinct('position')->count();
        
        return view('employees.index', compact(
            'employees', 
            'totalEmployees', 
            'activeEmployees', 
            'veteranEmployees', 
            'departmentsCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'position' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'is_active' => 'required|boolean',
        ]);

        $employee = Employee::create($validated);
        
        if ($request->ajax()) {
            return response()->json($employee, 201);
        }
        
        return redirect()->route('employees.index')->with('success', 'Employee added successfully!');
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:employees,email,' . $id,
            'position' => 'sometimes|string|max:255',
            'joining_date' => 'sometimes|date',
            'is_active' => 'sometimes|boolean',
        ]);

        $employee->update($validated);
        
        if ($request->ajax()) {
            return response()->json($employee);
        }
        
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        
        if (request()->ajax()) {
            return response()->json(null, 204);
        }
        
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}