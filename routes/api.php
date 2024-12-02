<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
  Route::get('test', function () {
    return response()->json([
        'message' => 'Hello from API v1!'
    ]);
  });
  Route::group([], __DIR__ . '/api/v1/companies.php');
  Route::group([], __DIR__ . '/api/v1/employees.php');
});
