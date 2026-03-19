@php
    $editing     = isset($curso);
    $nombre      = old('nombre',      $editing ? $curso->nombre      : '');
    $descripcion = old('descripcion', $editing ? $curso->descripcion : '');
    $nivel       = old('nivel',       $editing ? $curso->nivel       : '');
    $grado       = old('grado',       $editing ? $curso->grado       : '');
    $tipo        = old('tipo',        $editing ? $curso->tipo        : '');
    $activo      = old('activo',      $editing ? ($curso->activo ? '1' : '0') : '1');
@endphp

{{-- Sección 1: Información del curso --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4"
     x-data="{
         nivel: '{{ $nivel }}',
         activo: {{ $activo === '1' ? 'true' : 'false' }},
     }">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-primary-dark/10 flex items-center justify-center">
            <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Información del curso</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Nombre --}}
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Nombre del curso <span class="text-red-400">*</span>
            </label>
            <input type="text" name="nombre" value="{{ $nombre }}"
                   placeholder="Ej: Física, Matemática, Química..."
                   class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                          {{ $errors->has('nombre') ? 'border-red-400 bg-red-50 focus:ring-red-200' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            @error('nombre')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nivel --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Nivel <span class="text-red-400">*</span>
            </label>
            <select name="nivel" x-model="nivel"
                    class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                           {{ $errors->has('nivel') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
                <option value="">Seleccionar nivel...</option>
                <option value="pollito"    {{ $nivel === 'pollito'    ? 'selected' : '' }}>Pollito (Escolar)</option>
                <option value="intermedio" {{ $nivel === 'intermedio' ? 'selected' : '' }}>Intermedio (Pre-Universitario)</option>
                <option value="ambos"      {{ $nivel === 'ambos'      ? 'selected' : '' }}>Todos los niveles</option>
            </select>
            @error('nivel')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Grado (solo si nivel = pollito) --}}
        <div x-show="nivel === 'pollito'" x-transition>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Grado
            </label>
            <select name="grado"
                    class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                           border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20">
                <option value="">Sin especificar</option>
                @for($g = 1; $g <= 5; $g++)
                    <option value="{{ $g }}" {{ (int)$grado === $g ? 'selected' : '' }}>{{ $g }}.° grado</option>
                @endfor
            </select>
            @error('grado')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tipo --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Tipo <span class="text-red-400">*</span>
            </label>
            <select name="tipo"
                    class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                           {{ $errors->has('tipo') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
                <option value="">Seleccionar tipo...</option>
                <option value="reforzamiento"    {{ $tipo === 'reforzamiento'    ? 'selected' : '' }}>Reforzamiento</option>
                <option value="preuniversitario" {{ $tipo === 'preuniversitario' ? 'selected' : '' }}>Pre-Universitario</option>
            </select>
            @error('tipo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Descripción
            </label>
            <textarea name="descripcion" rows="3"
                      placeholder="Describe el contenido y objetivos de este curso..."
                      class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none resize-none
                             {{ $errors->has('descripcion') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">{{ $descripcion }}</textarea>
            @error('descripcion')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Toggle Activo --}}
        <div class="sm:col-span-2">
            <input type="hidden" name="activo" :value="activo ? '1' : '0'">
            <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition-colors cursor-pointer"
                 @click="activo = !activo">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                         :class="activo ? 'bg-emerald-100' : 'bg-gray-100'">
                        <svg class="w-4 h-4 transition-colors" :class="activo ? 'text-emerald-600' : 'text-gray-400'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Curso activo</p>
                        <p class="text-xs text-gray-400">Los cursos activos son visibles para los alumnos</p>
                    </div>
                </div>
                <div class="relative flex-shrink-0">
                    <div class="w-12 h-6 rounded-full transition-colors duration-200"
                         :class="activo ? 'bg-emerald-500' : 'bg-gray-200'"></div>
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200"
                         :class="activo ? 'translate-x-6' : 'translate-x-0'"></div>
                </div>
            </div>
        </div>

    </div>
</div>
