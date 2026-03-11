{{-- Partial compartido por create y edit --}}
{{-- Variables: $alumnos, $planes, $matricula (edit), $alumnoSeleccionado (create) --}}

@php
    $editing     = isset($matricula);
    $defaultTipo = old('tipo_pago', $editing ? $matricula->tipo_pago : 'completo');
    $defaultEst  = old('estado', $editing ? $matricula->estado : 'activa');
    $alumnosJson = '[]';
    if (!$editing && isset($alumnos)) {
        $alumnosJson = $alumnos->map(fn($a) => [
            'id'      => $a->id,
            'nombre'  => $a->nombreCompleto(),
            'dni'     => $a->dni,
            'tipo'    => $a->tipo,
            'inicial' => $a->inicial(),
        ])->toJson();
    }
    $alumnoSeleccionado = $alumnoSeleccionado ?? null;
    $initialAlumnoId    = old('alumno_id', isset($alumnoSeleccionado) ? ($alumnoSeleccionado->id ?? '') : '');
    $initialAlumnoLabel = isset($alumnoSeleccionado) && $alumnoSeleccionado
        ? $alumnoSeleccionado->nombreCompleto() . ' — DNI ' . $alumnoSeleccionado->dni : '';
@endphp

{{-- SECCIÓN 1: Alumno --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-primary-dark/10 flex items-center justify-center">
            <svg class="w-3.5 h-3.5 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Alumno</h3>
    </div>
    <div class="p-5">
        @if($editing)
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light
                            flex items-center justify-center font-black text-white text-sm flex-shrink-0">
                    {{ $matricula->alumno->inicial() }}
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-800">{{ $matricula->alumno->nombreCompleto() }}</p>
                    <p class="text-xs text-gray-400 font-mono">DNI: {{ $matricula->alumno->dni }}</p>
                </div>
                <span class="text-xs text-gray-400 italic px-2 py-1 bg-gray-100 rounded-lg">No editable</span>
                <input type="hidden" name="alumno_id" value="{{ $matricula->alumno_id }}">
            </div>
        @else
            {{-- Dropdown con búsqueda dinámica --}}
            <div x-data="{
                     open: false,
                     search: '',
                     selected: { id: '{{ $initialAlumnoId }}', nombre: '{{ addslashes($initialAlumnoLabel) }}', dni: '', tipo: '', inicial: '' },
                     options: {{ $alumnosJson }},
                     get filtered() {
                         if (!this.search) return this.options;
                         const s = this.search.toLowerCase();
                         return this.options.filter(o =>
                             o.nombre.toLowerCase().includes(s) || o.dni.includes(s)
                         );
                     },
                     select(opt) { this.selected = opt; this.open = false; this.search = ''; }
                 }"
                 @click.outside="open = false"
                 class="relative">

                <input type="hidden" name="alumno_id" :value="selected.id">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Alumno <span class="text-red-500">*</span>
                </label>

                <button type="button" @click="open = !open"
                        class="w-full px-4 py-3 rounded-xl border text-sm text-left flex items-center
                               justify-between gap-2 bg-gray-50 hover:border-accent transition-all
                               {{ $errors->has('alumno_id') ? 'border-red-400' : 'border-gray-200' }}">
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        <template x-if="selected.id">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-primary-dark to-primary-light
                                        flex items-center justify-center text-white text-xs font-black flex-shrink-0"
                                 x-text="selected.inicial || selected.nombre?.charAt(0)?.toUpperCase()"></div>
                        </template>
                        <span :class="selected.id ? 'text-gray-800 font-semibold' : 'text-gray-400'"
                              x-text="selected.id
                                  ? (selected.nombre + (selected.dni ? ' — DNI ' + selected.dni : ''))
                                  : '— Busca y selecciona un alumno —'"></span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Panel desplegable --}}
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute z-30 w-full mt-1 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="p-2 border-b border-gray-100">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input x-model="search" type="text"
                                   placeholder="Buscar por nombre o DNI..."
                                   @click.stop
                                   x-init="$watch('open', v => v && $nextTick(() => $el.focus()))"
                                   class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm
                                          outline-none focus:border-accent transition-all">
                        </div>
                    </div>
                    <div class="max-h-60 overflow-y-auto">
                        <template x-if="filtered.length === 0">
                            <p class="text-center text-gray-400 text-sm py-6">No se encontraron alumnos</p>
                        </template>
                        <template x-for="opt in filtered" :key="opt.id">
                            <button type="button" @click="select(opt)"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors
                                           flex items-center gap-3 border-b border-gray-50 last:border-0"
                                    :class="{ 'bg-accent/5': selected.id == opt.id }">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-xs font-black flex-shrink-0"
                                     :class="opt.tipo === 'intermedio'
                                         ? 'bg-gradient-to-br from-violet-400 to-purple-500'
                                         : 'bg-gradient-to-br from-sky-400 to-cyan-500'"
                                     x-text="opt.inicial"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-800" x-text="opt.nombre"></p>
                                    <p class="text-xs text-gray-400" x-text="'DNI: ' + opt.dni"></p>
                                </div>
                                <template x-if="selected.id == opt.id">
                                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </template>
                            </button>
                        </template>
                    </div>
                </div>

                @error('alumno_id')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>
        @endif
    </div>
</div>

{{-- SECCIÓN 2: Plan y vigencia --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4"
     x-data="{
         planId: '{{ old('plan_id', $matricula->plan_id ?? '') }}',
         planes: {{ $planes->map(fn($p) => ['id'=>$p->id,'nombre'=>$p->nombre,'precio'=>$p->precio,'duracion'=>$p->duracion_meses])->toJson() }},
         get planSel() { return this.planes.find(p => p.id == this.planId) ?? null; }
     }">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-accent/10 flex items-center justify-center">
            <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Plan y vigencia</h3>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            @if($editing)
                <div class="md:col-span-2 flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="w-9 h-9 rounded-lg bg-accent/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-800">{{ $matricula->plan->nombre }}</p>
                        <p class="text-xs text-gray-400">{{ $matricula->plan->duracion_meses }} mes(es) · {{ $matricula->plan->precioFormateado() }}</p>
                    </div>
                    <span class="text-xs text-gray-400 italic px-2 py-1 bg-gray-100 rounded-lg">No editable</span>
                </div>
            @else
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        Plan <span class="text-red-500">*</span>
                    </label>
                    <select name="plan_id" x-model="planId"
                            class="w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all
                                   bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20
                                   {{ $errors->has('plan_id') ? 'border-red-400' : 'border-gray-200' }}">
                        <option value="">— Selecciona un plan —</option>
                        @foreach($planes as $plan)
                            <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->nombre }} — {{ $plan->precioFormateado() }} · {{ $plan->duracion_meses }} mes(es)
                            </option>
                        @endforeach
                    </select>
                    @error('plan_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror

                    {{-- Preview plan --}}
                    <div x-show="planSel" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mt-3 grid grid-cols-3 gap-3 p-4 bg-gradient-to-r from-accent/5 to-primary-light/5
                                border border-accent/20 rounded-xl">
                        <div class="text-center">
                            <p class="text-xs text-gray-400 mb-0.5">Precio</p>
                            <p class="font-black text-gray-800 text-sm">S/. <span x-text="parseFloat(planSel?.precio ?? 0).toFixed(2)"></span></p>
                        </div>
                        <div class="text-center border-x border-accent/20">
                            <p class="text-xs text-gray-400 mb-0.5">Duración</p>
                            <p class="font-black text-gray-800 text-sm"><span x-text="planSel?.duracion"></span> mes(es)</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-400 mb-0.5">Plan</p>
                            <p class="font-bold text-accent text-xs" x-text="planSel?.nombre"></p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Fecha de inicio --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Fecha de inicio <span class="text-red-500">*</span>
                </label>
                <input type="date" name="fecha_inicio"
                       value="{{ old('fecha_inicio', $editing ? $matricula->fecha_inicio?->format('Y-m-d') : date('Y-m-d')) }}"
                       class="w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all
                              bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20
                              {{ $errors->has('fecha_inicio') ? 'border-red-400' : 'border-gray-200' }}">
                @error('fecha_inicio') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Días cortesía --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Días de cortesía
                    <span class="text-gray-400 font-normal normal-case">(suman al vencimiento)</span>
                </label>
                <div class="relative">
                    <input type="number" name="dias_cortesia" min="0" max="30"
                           value="{{ old('dias_cortesia', $editing ? $matricula->dias_cortesia : 0) }}"
                           placeholder="0"
                           class="w-full pl-4 pr-14 py-3 rounded-xl border text-sm outline-none transition-all
                                  bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20
                                  {{ $errors->has('dias_cortesia') ? 'border-red-400' : 'border-gray-200' }}">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">días</span>
                </div>
                @error('dias_cortesia') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>

{{-- SECCIÓN 3: Modalidad de pago --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Modalidad de pago</h3>
    </div>
    <div class="p-5">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
            Tipo de pago <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-3 gap-3">
            @foreach([
                'completo' => ['💳','Completo','Pago único'],
                'mensual'  => ['📅','Mensual','Cuota mensual'],
                'cuotas'   => ['📊','En cuotas','Varias cuotas']
            ] as $val => [$icon,$label,$desc])
            <label class="cursor-pointer">
                <input type="radio" name="tipo_pago" value="{{ $val }}" class="sr-only peer"
                       {{ $defaultTipo === $val ? 'checked' : '' }}>
                <div class="flex flex-col items-center gap-1.5 p-4 rounded-xl border-2 border-gray-200 text-center
                            peer-checked:border-accent peer-checked:bg-accent/5
                            hover:border-gray-300 transition-all duration-150 cursor-pointer">
                    <span class="text-2xl">{{ $icon }}</span>
                    <span class="text-xs font-bold text-gray-700">{{ $label }}</span>
                    <span class="text-xs text-gray-400">{{ $desc }}</span>
                </div>
            </label>
            @endforeach
        </div>
        @error('tipo_pago') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
    </div>
</div>

{{-- SECCIÓN 4: Estado (solo edición) --}}
@if($editing)
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-violet-100 flex items-center justify-center">
            <svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Estado de la matrícula</h3>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
            @php
                $estadosM = [
                    'activa'     => ['✅','Activa',    'peer-checked:border-emerald-400 peer-checked:bg-emerald-50'],
                    'pendiente'  => ['🟡','Pendiente', 'peer-checked:border-yellow-400 peer-checked:bg-yellow-50'],
                    'vencida'    => ['🔴','Vencida',   'peer-checked:border-red-400 peer-checked:bg-red-50'],
                    'suspendida' => ['⏸','Suspendida','peer-checked:border-gray-400 peer-checked:bg-gray-100'],
                ];
            @endphp
            @foreach($estadosM as $val => [$icon,$label,$cls])
            <label class="cursor-pointer">
                <input type="radio" name="estado" value="{{ $val }}" class="sr-only peer"
                       {{ $defaultEst === $val ? 'checked' : '' }}>
                <div class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 border-gray-200 text-center
                            {{ $cls }} hover:border-gray-300 transition-all duration-150">
                    <span class="text-xl">{{ $icon }}</span>
                    <span class="text-xs font-bold text-gray-600">{{ $label }}</span>
                </div>
            </label>
            @endforeach
        </div>
        @error('estado') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
    </div>
</div>
@endif

{{-- SECCIÓN 5: Observaciones --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center">
            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Observaciones</h3>
        <span class="ml-auto text-xs text-gray-400">Opcional</span>
    </div>
    <div class="p-5">
        <textarea name="observaciones" rows="3"
                  placeholder="Notas adicionales sobre la matrícula..."
                  class="w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all resize-none
                         bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20
                         {{ $errors->has('observaciones') ? 'border-red-400' : 'border-gray-200' }}">{{ old('observaciones', $matricula->observaciones ?? '') }}</textarea>
        @error('observaciones') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
    </div>
</div>