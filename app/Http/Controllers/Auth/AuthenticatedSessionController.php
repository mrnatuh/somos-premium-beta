<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['username'])
            ->orWhere('cc', $credentials['username'])
            ->first();

        if (!$user) {
            return redirect()->back()->withErrors(['username' => 'Email, Centro de custo ou senha inválida.']);
        }

        if (Auth::attempt(['email' => $user['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate(); // regenerate session for prevent session hijacking attack
            return redirect()->route('dashboard'); // redirect to admin index
        }

        return redirect()->back()->withErrors(['username' => __('Email, Centro de custo ou senha inválida.')]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Auth::guard('cc')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
