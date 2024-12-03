<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      $validApiKey = config('api.api_key');
      $apiKey = $request->header('x-api-key') ?: $request->query('api_key');

      if (!$apiKey || $apiKey !== $validApiKey) {
        return response()->json(['message' => 'Nieautoryzowany dostęp.'], 401);
      }

      return $next($request);
    }
}
