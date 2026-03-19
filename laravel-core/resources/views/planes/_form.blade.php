@php
    $editing = isset($plan);
    $nombre          = old('nombre',         $editing ? $plan->nombre         : '');
    $precio          = old('precio',         $editing ? $plan->precio         : '');
    $duracionMeses   = old('duracion_meses', $editing ? $plan->duracion_meses : '');
    $descripcion     = old('descripcion',    $editing ? $plan->descripcion    : '');
    $accesoIlimitado = old('acceso_ilimitado', $editing ? ($plan->acceso_ilimitado ? '1' : '0') : '0');
    $activo          = old('activo',           $editing ? ($plan->activo ? '1' : '0') : '1');
    $selectedCursos  = old('cursos', $editing ? $plan->cursos->pluck('id')->toArray() : []);
@endphp

{{-- Sección 1: Información del plan --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-primary-dark/10 flex items-center justify-center">
            <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Información del plan</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Nombre --}}
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Nombre del plan <span class="text-red-400">*</span>
            </label>
            <input type="text" name="nombre" value="{{ $nombre }}"
                   placeholder="Ej: Plan Premium, Plan VIP, Plan Básico..."
                   class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                          {{ $errors->has('nombre') ? 'border-red-400 bg-red-50 focus:ring-red-200' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            @error('nombre')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Precio --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Precio (S/.) <span class="text-red-400">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">S/.</span>
                <input type="number" name="precio" value="{{ $precio }}" step="0.01" min="0"
                       placeholder="0.00"
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                              {{ $errors->has('precio') ? 'border-red-400 bg-red-50 focus:ring-red-200' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            </div>
            @error('precio')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Duración --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Duración (meses) <span class="text-red-400">*</span>
            </label>
            <div class="relative">
                <input type="number" name="duracion_meses" value="{{ $duracionMeses }}" min="1" max="36"
                       placeholder="Ej: 1, 3, 6, 12..."
                       class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                              {{ $errors->has('duracion_meses') ? 'border-red-400 bg-red-50 focus:ring-red-200' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            </div>
            @error('duracion_meses')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Descripción
            </label>
            <textarea name="descripcion" rows="3"
                      placeholder="Describe qué incluye este plan (materias, beneficios, etc.)..."
                      class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none resize-none
                             {{ $errors->has('descripcion') ? 'border-red-400 bg-red-50 focus:ring-red-200' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">{{ $descripcion }}</textarea>
            @error('descripcion')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

    </div>
</div>

{{-- Sección 2: Cursos del plan --}}
@if(isset($cursos) && $cursos->isNotEmpty())
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4"
     x-data="{
         selected: {{ json_encode(array_map('intval', (array) $selectedCursos)) }},
         toggle(id) {
             const idx = this.selected.indexOf(id);
             idx === -1 ? this.selected.push(id) : this.selected.splice(idx, 1);
         },
         isSelected(id) { return this.selected.includes(id); }
     }">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-accent/10 flex items-center justify-center">
            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Cursos incluidos en este plan</h3>
        <span class="ml-auto text-xs text-gray-400 font-medium" x-text="selected.length + ' seleccionados'"></span>
    </div>
    <div class="p-5">
        <template x-for="id in selected" :key="id">
            <input type="hidden" name="cursos[]" :value="id">
        </template>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-72 overflow-y-auto pr-1">
            @foreach($cursos as $curso)
                <div @click="toggle({{ $curso->id }})"
                     :class="isSelected({{ $curso->id }})
                         ? 'border-accent bg-accent/5 ring-1 ring-accent/30'
                         : 'border-gray-200 bg-gray-50/50 hover:border-gray-300'"
                     class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all duration-150 select-none">
                    <div :class="isSelected({{ $curso->id }}) ? 'bg-accent' : 'bg-gray-200'"
                         class="w-5 h-5 rounded flex items-center justify-center flex-shrink-0 transition-colors">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             x-show="isSelected({{ $curso->id }})">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-700 leading-tight truncate">{{ $curso->nombre }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $curso->nivelLabel() }}
                            @if($curso->grado) · {{ $curso->gradoLabel() }} @endif
                        </p>
                    </div>
                    <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-full font-bold flex-shrink-0
                                 {{ $curso->tipo === 'preuniversitario' ? 'bg-violet-100 text-violet-600' : 'bg-blue-100 text-blue-600' }}">
                        {{ $curso->tipoLabel() }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Sección 2: Configuración --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4"
     x-data="{
         ilimitado: {{ $accesoIlimitado === '1' ? 'true' : 'false' }},
         activo: {{ $activo === '1' ? 'true' : 'false' }},
     }">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-accent/10 flex items-center justify-center">
            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Configuración</h3>
    </div>
    <div class="p-5 space-y-4">

        {{-- Hidden inputs --}}
        <input type="hidden" name="acceso_ilimitado" :value="ilimitado ? '1' : '0'">
        <input type="hidden" name="activo" :value="activo ? '1' : '0'">

        {{-- Toggle: Acceso Ilimitado --}}
        <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition-colors cursor-pointer"
             @click="ilimitado = !ilimitado">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                     :class="ilimitado ? 'bg-amber-100' : 'bg-gray-100'">
                    <svg class="w-4 h-4 transition-colors" :class="ilimitado ? 'text-amber-600' : 'text-gray-400'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Acceso ilimitado</p>
                    <p class="text-xs text-gray-400">El alumno tiene acceso sin fecha de vencimiento</p>
                </div>
            </div>
            <div class="relative flex-shrink-0">
                <div class="w-12 h-6 rounded-full transition-colors duration-200"
                     :class="ilimitado ? 'bg-amber-500' : 'bg-gray-200'">
                </div>
                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200"
                     :class="ilimitado ? 'translate-x-6' : 'translate-x-0'">
                </div>
            </div>
        </div>

        {{-- Toggle: Activo --}}
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
                    <p class="text-sm font-semibold text-gray-700">Plan activo</p>
                    <p class="text-xs text-gray-400">Los planes activos se muestran en la landing page y en el formulario de matrículas</p>
                </div>
            </div>
            <div class="relative flex-shrink-0">
                <div class="w-12 h-6 rounded-full transition-colors duration-200"
                     :class="activo ? 'bg-emerald-500' : 'bg-gray-200'">
                </div>
                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200"
                     :class="activo ? 'translate-x-6' : 'translate-x-0'">
                </div>
            </div>
        </div>

    </div>
</div>
