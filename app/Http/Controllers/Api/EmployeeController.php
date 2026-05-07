<?php
// app/Http/Controllers/Api/EmployeeController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{

   



    public function index(): JsonResponse
    {
        $employees = Employee::all();
        return response()->json($employees);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'position' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'is_active' => 'required|boolean',
        ]);

        $employee = Employee::create($validated);
        return response()->json($employee, 201);
    }

    public function show($id): JsonResponse
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    public function update(Request $request, $id): JsonResponse
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
        return response()->json($employee);
    }

    public function destroy($id): JsonResponse
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return response()->json(null, 204);
    }

    public function getActiveLongTerm(): JsonResponse
    {
        $employees = Employee::where('is_active', true)
            ->whereRaw('TIMESTAMPDIFF(YEAR, joining_date, CURDATE()) > 5')
            ->get();
        return response()->json($employees);
    }
}