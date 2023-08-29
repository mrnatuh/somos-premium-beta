<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardControllerIndex extends Controller
{
    public function __invoke()
    {
        if (auth()->user()) {
            return to_route('dashboard');
        }

        return to_route('login');
    }
}
