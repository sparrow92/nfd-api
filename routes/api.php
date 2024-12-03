<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
  Route::group([], __DIR__ . '/api/v1/companies.php');
  Route::group([], __DIR__ . '/api/v1/employees.php');
});
