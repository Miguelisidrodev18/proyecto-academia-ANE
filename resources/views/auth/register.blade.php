<x-guest-layout>

    <!-- Indicador de pasos -->
    <div class="flex items-center justify-center gap-2 mb-6">
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-green-500 text-white text-xs font-bold flex items-center justify-center shadow-md">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </span>
            <span class="text-xs font-semibold text-green-600">Verificado</span>
        </div>
        <div class="w-8 h-px bg-accent"></div>
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-accent text-white text-xs font-bold flex items-center justify-center shadow-md">
                2
            </span>
            <span class="text-xs font-semibold text-accent">Registro</span>
        </div>
    </div>

    <!-- Encabezado -->
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-black text-primary-dark">Crear cuenta</h1>
        <p class="text-gray-500 text-sm mt-1">Completa tus datos para registrarte</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
        @csrf

        <!-- Nombre -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                Nombre completo
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   @class([
                       'w-full px-4 py-2.5 rounded-xl border outline-none text-sm transition-all',
                       'focus:border-accent focus:ring-2 focus:ring-accent/20',
                       'border-red-400 bg-red-50'  => $errors->has('name'),
                       'border-gray-200'            => !$errors->has('name'),
                   ])
                   placeholder="Juan Pérez" required autofocus autocomplete="name" />
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

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
                   placeholder="correo@ejemplo.com" required autocomplete="username" />
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Rol -->
        <div>
            <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">
                Tipo de cuenta
            </label>
            <select id="role" name="role"
                    @class([
                        'w-full px-4 py-2.5 rounded-xl border outline-none text-sm transition-all bg-white',
                        'focus:border-accent focus:ring-2 focus:ring-accent/20',
                        'border-red-400 bg-red-50'  => $errors->has('role'),
                        'border-gray-200'            => !$errors->has('role'),
                    ])>
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Selecciona tu rol</option>
                @foreach(['alumno' => 'Alumno', 'docente' => 'Docente', 'representante' => 'Representante', 'admin' => 'Administrador'] as $val => $label)
                    <option value="{{ $val }}" {{ old('role') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('role')
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
                       placeholder="Mínimo 8 caracteres" required autocomplete="new-password" />
                <button type="button" onclick="toggleField('password','eye1-open','eye1-closed')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-accent transition-colors">
                    <svg id="eye1-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="eye1-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirmar contraseña -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">
                Confirmar contraseña
            </label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-200
                              focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none text-sm transition-all"
                       placeholder="Repite tu contraseña" required autocomplete="new-password" />
                <button type="button" onclick="toggleField('password_confirmation','eye2-open','eye2-closed')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-accent transition-colors">
                    <svg id="eye2-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="eye2-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Botón crear cuenta -->
        <button type="submit"
                class="w-full py-3 rounded-xl font-bold text-sm text-white mt-1
                       bg-gradient-to-r from-primary-dark to-primary-light
                       hover:from-accent hover:to-secondary
                       transition-all duration-300 shadow-md">
            Crear cuenta
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
        function toggleField(fieldId, openId, closedId) {
            const input  = document.getElementById(fieldId);
            const open   = document.getElementById(openId);
            const closed = document.getElementById(closedId);
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
