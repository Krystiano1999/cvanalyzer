<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Companies;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 0,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Display the registration employer view.
     */
    public function createEmployer(): Response
    {
        return Inertia::render('Auth/EmployerRegister');
    }

    /**
     * Handle an incoming registration employer request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeEmployer(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'nip' => 'nullable|string',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'user_type' => 1,
        ]);
    
        $company = Companies::create([
            'name' => $request->company,
            'description' => $request->description,
            'location' => $request->location,
            'nip' => $request->nip,
            'recruiter_id' => $user->id, 
        ]);
    
        event(new Registered($user));
    
        Auth::login($user);
    
        return redirect(RouteServiceProvider::PANEL);
    }
}
