<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class SetLocale
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $default = config('app.locale');

    $locale = $request->header('Accept-Language', $default);

    if ($queryLocale = $request->query('lang')) {
      $locale = $queryLocale;
    }

    if (in_array($locale, config('app.supported_locales'))) {
      App::setLocale($locale);
    }

    return $next($request);
  }
}
