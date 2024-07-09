<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsJobSeeker
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type === 0) {
            return $next($request);
        }

        // Tutaj przekieruj lub zwróć błąd, jeśli użytkownik nie jest szukającym pracy
        return redirect('/');
    }
}
