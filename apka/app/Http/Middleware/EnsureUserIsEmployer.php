<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsEmployer
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type === 1) {
            return $next($request);
        }

        // Tutaj przekieruj lub zwróć błąd, jeśli użytkownik nie jest pracodawcą
        return redirect('/');
    }
}
