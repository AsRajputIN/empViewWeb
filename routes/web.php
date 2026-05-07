<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeWebController;

Route::get('/', function () {
    return redirect()->route('employees.index');
});



Route::prefix('employees')->group(function () {
    Route::get('/', [EmployeeWebController::class, 'index'])->name('employees.index');
    Route::post('/', [EmployeeWebController::class, 'store'])->name('employees.store');
    Route::get('/{id}', [EmployeeWebController::class, 'show'])->name('employees.show');
    Route::put('/{id}', [EmployeeWebController::class, 'update'])->name('employees.update');
    Route::delete('/{id}', [EmployeeWebController::class, 'destroy'])->name('employees.destroy');
});
