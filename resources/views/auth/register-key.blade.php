<x-guest-layout>

    <!-- Indicador de pasos -->
    <div class="flex items-center justify-center gap-2 mb-6">
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-accent text-white text-xs font-bold flex items-center justify-center shadow-md">
                1
            </span>
            <span class="text-xs font-semibold text-accent">Verificación</span>
        </div>
        <div class="w-8 h-px bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-400 text-xs font-bold flex items-center justify-center">
                2
            </span>
            <span class="text-xs font-medium text-gray-400">Registro</span>
        </div>
    </div>

    <!-- Icono + Encabezado -->
    <div class="flex flex-col items-center mb-6">
        <div class="w-14 h-14 rounded-2xl bg-primary-dark flex items-center justify-center mb-4 shadow-lg">
            <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-black text-primary-dark">Acceso restringido</h1>
        <p class="text-gray-500 text-sm mt-1 text-center max-w-xs">
            Ingresa la clave maestra para continuar con el registro.
        </p>
    </div>

    <form method="POST" action="{{ route('register.verify-key') }}" class="flex flex-col gap-4">
        @csrf

        <!-- Clave maestra -->
        <div>
            <label for="clave_maestra" class="block text-sm font-semibold text-gray-700 mb-1">
                Clave maestra
            </label>
            <div class="relative">
                <input id="clave_maestra"
                       type="password"
                       name="clave_maestra"
                       class="w-full pl-4 pr-10 py-3 rounded-xl border
                              @error('clave_maestra') border-red-400 bg-red-50 @else border-gray-200 @enderror
                              focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none
                              text-sm transition-all duration-200"
                       placeholder="••••••••••••"
                       autocomplete="off"
                       autofocus />
                <!-- Toggle visibilidad -->
                <button type="button"
                        onclick="togglePass()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-accent transition-colors">
                    <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('clave_maestra')
                <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Aviso de seguridad -->
        <div class="flex items-start gap-2 bg-amber-50 border border-amber-200 rounded-xl p-3">
            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-xs text-amber-700 leading-relaxed">
                Solo el personal autorizado puede crear cuentas. Contacta al administrador si no tienes la clave.
            </p>
        </div>

        <!-- Botón continuar -->
        <button type="submit"
                class="w-full py-3 rounded-xl font-bold text-sm text-white mt-1
                       bg-gradient-to-r from-accent to-secondary
                       hover:from-secondary hover:to-accent
                       transition-all duration-300 shadow-md shadow-accent/25
                       flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
            Continuar
        </button>

        <!-- Link a login -->
        <p class="text-center text-sm text-gray-500">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}"
               class="text-accent font-semibold hover:text-secondary transition-colors duration-200">
                Inicia sesión
            </a>
        </p>
    </form>

    <script>
        function togglePass() {
            const input = document.getElementById('clave_maestra');
            const open  = document.getElementById('eye-open');
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
