<?php

namespace App\Http\Controllers;

class DashboardControllerIndex extends Controller
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function __invoke()
  {
    if (auth()->user()) {
      return to_route('dashboard');
    }

    return to_route('login');
  }
}
