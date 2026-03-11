{{-- Partial compartido entre create.blade.php y edit.blade.php --}}
{{-- Variables esperadas: $alumno (con ->user cargado, para edit) --}}

@php
    $editing        = isset($alumno);
    $userName       = $editing ? $alumno->user->name : '';
    $nameParts      = explode(' ', $userName, 2);
    $defaultNombres = $nameParts[0] ?? '';
    $defaultApell   = $nameParts[1] ?? '';
    $defaultEmail   = $editing ? ($alumno->user->email ?? '') : '';
    $defaultTipo    = old('tipo', $alumno->tipo ?? 'pollito');
    $defaultEstado  = old('estado', $editing ? ($alumno->estado ? 'activo' : 'inactivo') : 'activo');
@endphp

{{-- SECCIÓN 1: Identificación --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-primary-dark/10 flex items-center justify-center">
            <svg class="w-3.5 h-3.5 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Identificación</h3>
    </div>
    <div class="p-5" x-data="{
        dni: '{{ old('dni', $alumno->dni ?? '') }}',
        buscando: false,
        reniecOk: false,
        reniecError: '',
        async buscarReniec() {
            if (this.dni.length !== 8 || !/^\d{8}$/.test(this.dni)) return;
            this.buscando = true; this.reniecOk = false; this.reniecError = '';
            try {
                const res  = await fetch('{{ url('/alumnos/dni') }}/' + this.dni);
                const data = await res.json();
                if (!res.ok || data.error) { this.reniecError = data.error ?? 'No se encontró el DNI.'; return; }
                document.getElementById('input-nombres').value   = data.nombres ?? '';
                document.getElementById('input-apellidos').value = ((data.apellidoPaterno ?? '') + ' ' + (data.apellidoMaterno ?? '')).trim();
                this.reniecOk = true;
            } catch(e) {
                this.reniecError = 'Error al consultar RENIEC.';
            } finally { this.buscando = false; }
        }
    }">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- DNI --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    DNI <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" name="dni" maxlength="8"
                           x-model="dni"
                           @input="if(dni.length === 8) buscarReniec()"
                           placeholder="12345678"
                           @class([
                               'w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all pr-12 font-mono tracking-widest',
                               'focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white',
                               'border-red-400 bg-red-50' => $errors->has('dni'),
                               'border-gray-200'           => !$errors->has('dni'),
                           ])>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        <svg x-show="buscando" class="w-4 h-4 text-accent animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        <svg x-show="reniecOk && !buscando" class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p x-show="reniecOk" x-cloak class="text-emerald-600 text-xs mt-1.5 font-semibold flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Datos cargados desde RENIEC
                </p>
                <p x-show="reniecError" x-cloak x-text="reniecError" class="text-red-500 text-xs mt-1.5"></p>
                @error('dni') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Nivel --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Nivel <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2">
                    @foreach(['pollito' => ['🐣', 'Pollito', 'Nivelación Sec.'], 'intermedio' => ['⚡', 'Intermedio', 'Preuniversitario']] as $val => [$icon, $label, $desc])
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="tipo" value="{{ $val }}" class="sr-only peer"
                               {{ $defaultTipo === $val ? 'checked' : '' }}>
                        <div class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 border-gray-200 text-center
                                    peer-checked:border-accent peer-checked:bg-accent/5 peer-checked:text-accent
                                    hover:border-gray-300 transition-all duration-150 cursor-pointer">
                            <span class="text-xl">{{ $icon }}</span>
                            <span class="text-xs font-bold">{{ $label }}</span>
                            <span class="text-xs text-gray-400 peer-checked:text-accent/70">{{ $desc }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('tipo') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>

{{-- SECCIÓN 2: Datos personales --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-accent/10 flex items-center justify-center">
            <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Datos personales</h3>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Nombres --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Nombres <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombres" id="input-nombres"
                       value="{{ old('nombres', $defaultNombres) }}"
                       placeholder="Juan Carlos"
                       @class([
                           'w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all',
                           'focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white',
                           'border-red-400 bg-red-50' => $errors->has('nombres'),
                           'border-gray-200'           => !$errors->has('nombres'),
                       ])>
                @error('nombres') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Apellidos --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Apellidos <span class="text-red-500">*</span>
                </label>
                <input type="text" name="apellidos" id="input-apellidos"
                       value="{{ old('apellidos', $defaultApell) }}"
                       placeholder="Pérez García"
                       @class([
                           'w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all',
                           'focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white',
                           'border-red-400 bg-red-50' => $errors->has('apellidos'),
                           'border-gray-200'           => !$errors->has('apellidos'),
                       ])>
                @error('apellidos') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Correo electrónico <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input type="email" name="email"
                           value="{{ old('email', $defaultEmail) }}"
                           placeholder="correo@ejemplo.com"
                           @class([
                               'w-full pl-10 pr-4 py-3 rounded-xl border text-sm outline-none transition-all',
                               'focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white',
                               'border-red-400 bg-red-50' => $errors->has('email'),
                               'border-gray-200'           => !$errors->has('email'),
                           ])>
                </div>
                @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Teléfono --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Teléfono
                    <span class="text-gray-400 font-normal normal-case">(opcional)</span>
                </label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <input type="text" name="telefono" maxlength="9"
                           value="{{ old('telefono', $alumno->telefono ?? '') }}"
                           placeholder="987654321"
                           @class([
                               'w-full pl-10 pr-4 py-3 rounded-xl border text-sm outline-none transition-all',
                               'focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white',
                               'border-red-400 bg-red-50' => $errors->has('telefono'),
                               'border-gray-200'           => !$errors->has('telefono'),
                           ])>
                </div>
                @error('telefono') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>

{{-- SECCIÓN 3: Configuración --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-violet-100 flex items-center justify-center">
            <svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Configuración</h3>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Estado --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Estado <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="estado" value="activo" class="sr-only peer"
                               {{ $defaultEstado === 'activo' ? 'checked' : '' }}>
                        <div class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-gray-200
                                    peer-checked:border-emerald-400 peer-checked:bg-emerald-50 peer-checked:text-emerald-700
                                    hover:border-gray-300 transition-all duration-150 cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 peer-checked:animate-pulse"></span>
                            <span class="text-sm font-bold">Activo</span>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="estado" value="inactivo" class="sr-only peer"
                               {{ $defaultEstado === 'inactivo' ? 'checked' : '' }}>
                        <div class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-gray-200
                                    peer-checked:border-gray-400 peer-checked:bg-gray-50 peer-checked:text-gray-600
                                    hover:border-gray-300 transition-all duration-150 cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                            <span class="text-sm font-bold">Inactivo</span>
                        </div>
                    </label>
                </div>
                @error('estado') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Origen de registro --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Origen de registro
                </label>
                <input type="text" name="origen_registro"
                       value="{{ old('origen_registro', $alumno->origen_registro ?? 'manual') }}"
                       placeholder="manual, web, referido..."
                       @class([
                           'w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all',
                           'focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white',
                           'border-red-400 bg-red-50' => $errors->has('origen_registro'),
                           'border-gray-200'           => !$errors->has('origen_registro'),
                       ])>
                @error('origen_registro') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>
