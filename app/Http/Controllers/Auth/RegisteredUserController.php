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
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Paso 1: mostrar formulario de clave maestra o, si ya fue verificada, el formulario de registro.
     */
    public function create(): Response
    {
        $view = session('master_key_verified') === true
            ? view('auth.register')
            : view('auth.register-key');

        return response($view)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    /**
     * Paso 1 (POST): validar la clave maestra y marcar sesión.
     */
    public function verifyKey(Request $request): RedirectResponse
    {
        $request->validate([
            'clave_maestra' => ['required', 'string'],
        ]);

        if ($request->clave_maestra !== config('app.master_key')) {
            return back()->withErrors([
                'clave_maestra' => 'La clave maestra es incorrecta.',
            ]);
        }

        $request->session()->put('master_key_verified', true);

        return redirect()->route('register');
    }

    /**
     * Paso 2 (POST): crear la cuenta (sesión ya verificada).
     */
    public function store(Request $request): RedirectResponse
    {
        if (!session('master_key_verified')) {
            return redirect()->route('register');
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role'     => ['required', 'in:admin,docente,alumno,representante'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Login primero (migrate() regenera el ID de sesión)
        // luego forget para que el flag se elimine de la sesión NUEVA
        Auth::login($user);
        $request->session()->forget('master_key_verified');

        return redirect(route('dashboard', absolute: false));
    }
}
