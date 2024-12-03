<?php

use App\Http\Controllers\Api\V1\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('employees', [EmployeeController::class, 'index']);
Route::get('companies/{companyId}/employees', [EmployeeController::class, 'indexByCompany']);
Route::post('companies/{companyId}/employees', [EmployeeController::class, 'store']);
Route::get('employees/{id}', [EmployeeController::class, 'show']);
Route::put('employees/{id}', [EmployeeController::class, 'update']);
Route::delete('employees/{id}', [EmployeeController::class, 'destroy']);