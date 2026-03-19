{{-- Partial compartido por create y edit --}}
{{-- Variables: $alumnos, $planes, $matricula (edit), $alumnoSeleccionado (create) --}}

@php
    $editing     = isset($matricula);
    $defaultTipo = old('tipo_pago', $editing ? $matricula->tipo_pago : 'completo');
    $defaultEst  = old('estado', $editing ? $matricula->estado : 'activa');
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
            {{-- Búsqueda Select2 de alumno --}}
            <input type="hidden" name="alumno_id" id="alumno_id" value="{{ old('alumno_id', $initialAlumnoId) }}">
            <input type="hidden" name="confirmar_duplicada" id="confirmar_duplicada" value="0">

            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                Alumno <span class="text-red-500">*</span>
            </label>

            <select id="alumno_select" name="_alumno_select" style="width:100%">
                @if($initialAlumnoId)
                    <option value="{{ $initialAlumnoId }}" selected>{{ $initialAlumnoLabel }}</option>
                @else
                    <option></option>
                @endif
            </select>

            {{-- Advertencia matrícula activa --}}
            <div id="advertencia-container"
                 class="mt-3 p-4 bg-amber-50 border border-amber-200 rounded-xl"
                 style="display:none">
                <div class="flex gap-3">
                    <span class="text-xl flex-shrink-0">⚠️</span>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-amber-800">Este alumno ya tiene una matrícula activa</p>
                        <p class="text-xs text-amber-600 mt-1">
                            Plan: <span class="font-semibold" id="adv-plan"></span><br>
                            Vigencia: <span id="adv-inicio"></span> hasta <span id="adv-fin"></span>
                        </p>
                        <label class="flex items-center gap-2 mt-3 cursor-pointer">
                            <input type="checkbox" id="confirmar_checkbox"
                                   class="w-4 h-4 rounded border-amber-400 accent-amber-500"
                                   onchange="
                                       document.getElementById('confirmar_duplicada').value = this.checked ? '1' : '0';
                                       window.dispatchEvent(new CustomEvent('bloquear-submit', { detail: { bloqueado: !this.checked } }));
                                   ">
                            <span class="text-xs text-amber-700 font-medium">Confirmo que deseo crear una matrícula adicional</span>
                        </label>
                    </div>
                </div>
            </div>

            @error('alumno_id')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
            @enderror
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
                        <option value="{{ $plan->id }}"
                            {{ old('plan_id', $editing ? $matricula->plan_id : '') == $plan->id ? 'selected' : '' }}>
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