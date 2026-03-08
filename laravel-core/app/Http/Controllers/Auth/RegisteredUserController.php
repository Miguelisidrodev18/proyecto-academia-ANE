<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\Response;

class RegisteredUserController extends Controller
{
    /**
     * Paso 1: siempre mostrar el formulario de clave maestra.
     */
    public function create(): Response
    {
        return response(view('auth.register-key'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    /**
     * Paso 1 (POST): validar la clave maestra y devolver el formulario de registro.
     */
    public function verifyKey(Request $request): Response|RedirectResponse
    {
        $request->validate([
            'clave_maestra' => ['required', 'string'],
        ]);

        if ($request->clave_maestra !== config('app.master_key')) {
            return back()->withErrors(['clave_maestra' => 'La clave maestra es incorrecta.']);
        }

        // Pasar la clave a la vista Step 2 como campo oculto (sin sesión)
        return response(view('auth.register', ['masterKey' => $request->clave_maestra]))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    /**
     * Paso 2 (POST): crear la cuenta (re-valida la clave maestra del campo oculto).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'clave_maestra' => ['required', 'string'],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role'          => ['required', 'in:admin,docente,alumno,representante'],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->clave_maestra !== config('app.master_key')) {
            return redirect()->route('register')
                ->withErrors(['clave_maestra' => 'La clave maestra es incorrecta.']);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
