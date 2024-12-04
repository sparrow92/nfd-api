<?php

use App\Http\Middleware\ResultsPerPage;
use App\Http\Middleware\VerifyApiKey;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::middleware([ResultsPerPage::class, VerifyApiKey::class, SetLocale::class])->prefix('v1')->group(function () {
  Route::group([], __DIR__ . '/api/v1/companies.php');
  Route::group([], __DIR__ . '/api/v1/employees.php');
});
