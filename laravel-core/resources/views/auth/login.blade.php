<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Encabezado -->
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-black text-primary-dark">Iniciar sesión</h1>
        <p class="text-gray-500 text-sm mt-1">Accede a tu cuenta de Academia Nueva Era</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                Correo electrónico
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   @class([
                       'w-full px-4 py-2.5 rounded-xl border outline-none text-sm transition-all',
                       'focus:border-accent focus:ring-2 focus:ring-accent/20',
                       'border-red-400 bg-red-50'  => $errors->has('email'),
                       'border-gray-200'            => !$errors->has('email'),
                   ])
                   required autofocus autocomplete="username"
                   placeholder="correo@ejemplo.com" />
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Contraseña -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">
                Contraseña
            </label>
            <div class="relative">
                <input id="password" type="password" name="password"
                       @class([
                           'w-full pl-4 pr-10 py-2.5 rounded-xl border outline-none text-sm transition-all',
                           'focus:border-accent focus:ring-2 focus:ring-accent/20',
                           'border-red-400 bg-red-50'  => $errors->has('password'),
                           'border-gray-200'            => !$errors->has('password'),
                       ])
                       required autocomplete="current-password"
                       placeholder="••••••••" />
                <button type="button" onclick="togglePass()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-accent transition-colors">
                    <svg id="eye-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Recordarme + Olvidé contraseña -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-accent shadow-sm focus:ring-accent"
                       name="remember">
                <span class="text-sm text-gray-600">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm text-accent hover:text-secondary transition-colors duration-200">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Botón ingresar -->
        <button type="submit"
                class="w-full py-3 rounded-xl font-bold text-sm text-white mt-1
                       bg-gradient-to-r from-accent to-secondary
                       hover:from-secondary hover:to-accent
                       transition-all duration-300 shadow-md shadow-accent/25">
            Ingresar
        </button>

        <!-- Link a registro -->
        <p class="text-center text-sm text-gray-500">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}"
               class="text-accent font-semibold hover:text-secondary transition-colors duration-200">
                Regístrate
            </a>
        </p>
    </form>

    <script>
        function togglePass() {
            const input  = document.getElementById('password');
            const open   = document.getElementById('eye-open');
            const closed = document.getElementById('eye-closed');
            if (input.type === 'password') {
                input.type = 'text';
                open.classList.add('hidden');
                closed.classList.remove('hidden');
            } else {
                input.type = 'password';
                open.classList.remove('hidden');
                closed.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>
