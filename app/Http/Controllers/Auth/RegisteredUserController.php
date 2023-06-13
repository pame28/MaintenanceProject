<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $departments = Department::all();
        return view('auth.register', ['departments' => $departments]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'department_id' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ],[
            'name.required' => 'Por favor, introduce tu nombre.',
            'email.required' => 'Por favor, introduce tu email.',
            'email.unique' => 'El email introducido ya está en uso.',
            'password.required' => 'Por favor, introduce una contraseña.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'department_id.required' => 'Por favor, selecciona un departamento.'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'password' => Hash::make($request->password),
        ]);

        /* event(new Registered($user));

        Auth::login($user); */

        return to_route('platform.login')->with('success', 'Usuario registrado correctamente, contacta con el administrador para activar tu cuenta.');
    }
}
