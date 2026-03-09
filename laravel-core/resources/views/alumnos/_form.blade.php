{{-- Partial compartido entre create.blade.php y edit.blade.php --}}
{{-- Variables esperadas: $alumno (opcional, para edit) --}}

@php $editing = isset($alumno); @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-5"
     x-data="{
         dni: '{{ old('dni', $alumno->dni ?? '') }}',
         buscando: false,
         reniecOk: false,
         reniecError: '',
         async buscarReniec() {
             if (this.dni.length !== 8 || !/^\d{8}$/.test(this.dni)) return;
             this.buscando = true;
             this.reniecOk = false;
             this.reniecError = '';
             try {
                 const res = await fetch('{{ route('alumnos.dni', '') }}/' + this.dni);
                 const data = await res.json();
                 if (!res.ok || data.error) {
                     this.reniecError = data.error ?? 'No se encontró el DNI.';
                     return;
                 }
                 document.getElementById('input-nombres').value   = data.nombres ?? '';
                 document.getElementById('input-apellidos').value = ((data.apellidoPaterno ?? '') + ' ' + (data.apellidoMaterno ?? '')).trim();
                 this.reniecOk = true;
             } catch(e) {
                 this.reniecError = 'Error al consultar RENIEC.';
             } finally {
                 this.buscando = false;
             }
         }
     }">

    {{-- DNI --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            DNI <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <input type="text" name="dni" maxlength="8"
                   id="input-dni"
                   x-model="dni"
                   @input="if(dni.length === 8) buscarReniec()"
                   placeholder="12345678"
                   @class([
                       'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all pr-10',
                       'focus:border-accent focus:ring-2 focus:ring-accent/20',
                       'border-red-400 bg-red-50' => $errors->has('dni'),
                       'border-gray-200'           => !$errors->has('dni'),
                   ])>
            {{-- Spinner / estado --}}
            <div class="absolute right-3 top-1/2 -translate-y-1/2">
                <svg x-show="buscando" class="w-4 h-4 text-accent animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                <svg x-show="reniecOk && !buscando" class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
        <p x-show="reniecOk" x-cloak class="text-green-600 text-xs mt-1 font-medium">Datos cargados desde RENIEC</p>
        <p x-show="reniecError" x-cloak x-text="reniecError" class="text-red-500 text-xs mt-1"></p>
        @error('dni')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tipo --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Tipo de alumno <span class="text-red-500">*</span>
        </label>
        <select name="tipo"
                @class([
                    'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all bg-white',
                    'focus:border-accent focus:ring-2 focus:ring-accent/20',
                    'border-red-400 bg-red-50' => $errors->has('tipo'),
                    'border-gray-200'           => !$errors->has('tipo'),
                ])>
            <option value="vip"     {{ old('tipo', $alumno->tipo ?? 'vip') === 'vip'     ? 'selected' : '' }}>
                VIP
            </option>
            <option value="premium" {{ old('tipo', $alumno->tipo ?? '') === 'premium' ? 'selected' : '' }}>
                ⭐ Premium
            </option>
        </select>
        @error('tipo')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Nombres --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Nombres <span class="text-red-500">*</span>
        </label>
        <input type="text" name="nombres"
               id="input-nombres"
               value="{{ old('nombres', $alumno->nombres ?? '') }}"
               placeholder="Juan Carlos"
               @class([
                   'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all',
                   'focus:border-accent focus:ring-2 focus:ring-accent/20',
                   'border-red-400 bg-red-50' => $errors->has('nombres'),
                   'border-gray-200'           => !$errors->has('nombres'),
               ])>
        @error('nombres')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Apellidos --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Apellidos <span class="text-red-500">*</span>
        </label>
        <input type="text" name="apellidos"
               id="input-apellidos"
               value="{{ old('apellidos', $alumno->apellidos ?? '') }}"
               placeholder="Pérez García"
               @class([
                   'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all',
                   'focus:border-accent focus:ring-2 focus:ring-accent/20',
                   'border-red-400 bg-red-50' => $errors->has('apellidos'),
                   'border-gray-200'           => !$errors->has('apellidos'),
               ])>
        @error('apellidos')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Correo electrónico <span class="text-red-500">*</span>
        </label>
        <input type="email" name="email"
               value="{{ old('email', $alumno->email ?? '') }}"
               placeholder="correo@ejemplo.com"
               @class([
                   'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all',
                   'focus:border-accent focus:ring-2 focus:ring-accent/20',
                   'border-red-400 bg-red-50' => $errors->has('email'),
                   'border-gray-200'           => !$errors->has('email'),
               ])>
        @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Teléfono --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Teléfono
        </label>
        <input type="text" name="telefono" maxlength="9"
               value="{{ old('telefono', $alumno->telefono ?? '') }}"
               placeholder="987654321"
               @class([
                   'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all',
                   'focus:border-accent focus:ring-2 focus:ring-accent/20',
                   'border-red-400 bg-red-50' => $errors->has('telefono'),
                   'border-gray-200'           => !$errors->has('telefono'),
               ])>
        @error('telefono')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Fecha de nacimiento --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Fecha de nacimiento
        </label>
        <input type="date" name="fecha_nacimiento"
               value="{{ old('fecha_nacimiento', isset($alumno->fecha_nacimiento) ? $alumno->fecha_nacimiento->format('Y-m-d') : '') }}"
               max="{{ now()->subDay()->format('Y-m-d') }}"
               @class([
                   'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all',
                   'focus:border-accent focus:ring-2 focus:ring-accent/20',
                   'border-red-400 bg-red-50' => $errors->has('fecha_nacimiento'),
                   'border-gray-200'           => !$errors->has('fecha_nacimiento'),
               ])>
        @error('fecha_nacimiento')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Estado --}}
    <div class="flex items-center gap-3 pt-6">
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="hidden" name="estado" value="0">
            <input type="checkbox" name="estado" value="1"
                   class="sr-only peer"
                   {{ old('estado', $alumno->estado ?? true) ? 'checked' : '' }}>
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                        peer-checked:after:translate-x-full peer-checked:after:border-white
                        after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                        after:bg-white after:border-gray-300 after:border after:rounded-full
                        after:h-5 after:w-5 after:transition-all peer-checked:bg-accent"></div>
        </label>
        <span class="text-sm font-semibold text-gray-700">Alumno activo</span>
    </div>

    {{-- Dirección (ancho completo) --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Dirección
        </label>
        <textarea name="direccion" rows="2"
                  placeholder="Av. Los Álamos 123, San Isidro, Lima"
                  @class([
                      'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all resize-none',
                      'focus:border-accent focus:ring-2 focus:ring-accent/20',
                      'border-red-400 bg-red-50' => $errors->has('direccion'),
                      'border-gray-200'           => !$errors->has('direccion'),
                  ])>{{ old('direccion', $alumno->direccion ?? '') }}</textarea>
        @error('direccion')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>
