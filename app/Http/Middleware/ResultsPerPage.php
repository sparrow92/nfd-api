<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultsPerPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      $default = config('api.per_page') ?? 10;

      $perPage = $request->input('per_page', $default);
      $perPage = is_numeric($perPage) && $perPage > 0 ? (int) $perPage : $default;
      $perPage = min($perPage, 100);

      $request->merge(['per_page' => $perPage]);

      return $next($request);
    }
}
