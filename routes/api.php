<?php

use App\Http\Middleware\ResultsPerPage;
use Illuminate\Support\Facades\Route;

Route::middleware([ResultsPerPage::class])->prefix('v1')->group(function () {
  Route::group([], __DIR__ . '/api/v1/companies.php');
  Route::group([], __DIR__ . '/api/v1/employees.php');
});
