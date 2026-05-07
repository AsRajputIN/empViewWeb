<?php
// routes/api.php

use App\Http\Controllers\Api\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::apiResource('employees', EmployeeController::class);
Route::get('employees/active/long-term', [EmployeeController::class, 'getActiveLongTerm']);

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});