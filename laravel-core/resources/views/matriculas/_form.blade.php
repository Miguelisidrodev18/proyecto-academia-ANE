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
         planId: '{{ old('plan_id', $editing ? $matricula->plan_id : '') }}',
         modalOpen: false,
         planes: {{ $planes->map(fn($p) => ['id'=>$p->id,'nombre'=>$p->nombre,'precio'=>$p->precio,'duracion'=>$p->duracion_meses,'descripcion'=>$p->descripcion ?? '','acceso'=>(bool)$p->acceso_ilimitado])->toJson() }},
         get planSel() { return this.planes.find(p => p.id == this.planId) ?? null; },
         selectPlan(id) { this.planId = id; this.modalOpen = false; }
     }">

    {{-- Header --}}
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5 rounded-t-2xl">
        <div class="w-7 h-7 rounded-lg bg-accent/10 flex items-center justify-center">
            <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Plan y vigencia</h3>
        <button type="button" @click="modalOpen = true"
                class="ml-auto inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold
                       text-accent border border-accent/30 hover:bg-accent hover:text-white hover:border-accent
                       transition-all duration-150">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span x-text="planSel ? 'Cambiar plan' : 'Seleccionar plan'"></span>
        </button>
    </div>

    <div class="p-5">
        <input type="hidden" name="plan_id" :value="planId">
        @error('plan_id') <p class="text-red-500 text-xs mb-4 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ $message }}
        </p> @enderror

        {{-- ── ESTADO: Plan seleccionado ── --}}
        <div x-show="planSel" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="relative mb-5 rounded-2xl overflow-hidden border border-accent/20
                    bg-gradient-to-br from-primary-dark/5 via-accent/5 to-primary-light/5">

            {{-- Fondo decorativo --}}
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="absolute -top-6 -right-6 w-28 h-28 rounded-full bg-accent/10 blur-2xl"></div>
                <div class="absolute -bottom-4 -left-4 w-20 h-20 rounded-full bg-primary-light/10 blur-xl"></div>
            </div>

            <div class="relative p-5">
                {{-- Fila superior --}}
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <p class="text-xs font-bold text-accent/70 uppercase tracking-widest mb-0.5">Plan activo</p>
                        <h4 class="text-xl font-black text-primary-dark leading-tight" x-text="planSel?.nombre"></h4>
                    </div>
                    <div x-show="planSel?.acceso" x-cloak
                         class="flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full
                                bg-emerald-100 border border-emerald-200 text-emerald-700 text-xs font-bold">
                        <span>♾️</span> <span>Ilimitado</span>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-white/70 backdrop-blur-sm rounded-xl p-3 border border-white/50">
                        <p class="text-xs text-gray-400 mb-1">Precio</p>
                        <p class="text-lg font-black text-primary-dark">
                            S/. <span x-text="parseFloat(planSel?.precio ?? 0).toFixed(2)"></span>
                        </p>
                    </div>
                    <div class="bg-white/70 backdrop-blur-sm rounded-xl p-3 border border-white/50">
                        <p class="text-xs text-gray-400 mb-1">Duración</p>
                        <p class="text-lg font-black text-primary-dark">
                            <span x-text="planSel?.duracion"></span>
                            <span class="text-sm font-semibold text-gray-500"> mes(es)</span>
                        </p>
                    </div>
                </div>

                {{-- Descripción --}}
                <p x-show="planSel?.descripcion" x-cloak
                   class="text-xs text-gray-500 leading-relaxed mb-3"
                   x-text="planSel?.descripcion"></p>

                {{-- Botón cambiar --}}
                <button type="button" @click="modalOpen = true"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold
                               bg-white border border-accent/30 text-accent hover:bg-accent hover:text-white
                               hover:border-accent transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Cambiar plan
                </button>
            </div>
        </div>

        {{-- ── ESTADO: Sin plan ── --}}
        <div x-show="!planSel"
             class="mb-5 border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center
                    hover:border-accent/40 hover:bg-accent/2 transition-all duration-200 cursor-pointer group"
             @click="modalOpen = true">
            <div class="w-14 h-14 rounded-2xl bg-gray-100 group-hover:bg-accent/10 flex items-center justify-center mx-auto mb-3 transition-colors">
                <svg class="w-7 h-7 text-gray-300 group-hover:text-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 16.477 5.754 16 7.5 16s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 16.477 18.247 16 16.5 16c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-gray-500 group-hover:text-gray-700 mb-1 transition-colors">Ningún plan seleccionado</p>
            <p class="text-xs text-gray-400 mb-4">Haz clic para elegir el plan de membresía</p>
            <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold
                         bg-gradient-to-r from-primary-dark to-primary-light text-white
                         shadow-md group-hover:shadow-lg group-hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Seleccionar plan
            </span>
        </div>

        {{-- ── Fecha de inicio + Días cortesía ── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

    {{-- ── MODAL: Seleccionar plan ── --}}
    <template x-teleport="body">
        <div x-show="modalOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="modalOpen = false"
             @keydown.escape.window="modalOpen = false"
             class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">

            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden">

                {{-- Modal header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0
                            bg-gradient-to-r from-primary-dark/3 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-accent/10 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 16.477 5.754 16 7.5 16s3.332.477 4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-primary-dark">Seleccionar Plan</h2>
                            <p class="text-xs text-gray-400">Elige el plan de membresía para esta matrícula</p>
                        </div>
                    </div>
                    <button type="button" @click="modalOpen = false"
                            class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500
                                   hover:bg-red-100 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal body --}}
                <div class="overflow-y-auto p-6 flex-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <template x-for="plan in planes" :key="plan.id">
                            <button type="button"
                                    @click="selectPlan(plan.id)"
                                    :class="planId == plan.id
                                        ? 'border-accent ring-2 ring-accent/25 bg-gradient-to-br from-accent/8 to-primary-light/8 shadow-md'
                                        : 'border-gray-200 bg-white hover:border-accent/50 hover:shadow-md'"
                                    class="relative text-left rounded-2xl border-2 p-5 transition-all duration-200 group overflow-hidden">

                                {{-- Fondo decorativo al seleccionar --}}
                                <div x-show="planId == plan.id"
                                     class="absolute -top-4 -right-4 w-20 h-20 rounded-full bg-accent/10 blur-xl pointer-events-none"></div>

                                {{-- Checkmark --}}
                                <div x-show="planId == plan.id"
                                     class="absolute top-3.5 right-3.5 w-6 h-6 rounded-full bg-accent flex items-center justify-center shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>

                                {{-- Badge ilimitado --}}
                                <div x-show="plan.acceso"
                                     class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5
                                            bg-emerald-100 text-emerald-600 rounded-full mb-3">
                                    <span>♾️</span><span>Acceso ilimitado</span>
                                </div>
                                <div x-show="!plan.acceso" class="h-5 mb-3"></div>

                                {{-- Nombre --}}
                                <h4 class="font-black text-gray-800 text-base leading-tight mb-2" x-text="plan.nombre"></h4>

                                {{-- Precio --}}
                                <div class="flex items-baseline gap-0.5 mb-3">
                                    <span class="text-lg font-black text-primary-dark/60">S/.</span>
                                    <span class="text-3xl font-black text-primary-dark"
                                          x-text="parseFloat(plan.precio).toFixed(2)"></span>
                                </div>

                                {{-- Duración --}}
                                <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-3">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span x-text="plan.duracion + ' mes' + (plan.duracion > 1 ? 'es' : '') + ' de duración'"></span>
                                </div>

                                {{-- Descripción --}}
                                <p x-show="plan.descripcion"
                                   class="text-xs text-gray-400 leading-relaxed line-clamp-2 mb-3"
                                   x-text="plan.descripcion"></p>

                                {{-- CTA footer --}}
                                <div class="pt-3 border-t border-gray-100">
                                    <span class="text-xs font-bold transition-colors"
                                          :class="planId == plan.id ? 'text-accent' : 'text-gray-400 group-hover:text-accent'"
                                          x-text="planId == plan.id ? '✓ Plan seleccionado' : 'Seleccionar →'"></span>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Modal footer --}}
                <div class="px-6 py-4 border-t border-gray-100 flex-shrink-0 flex justify-end">
                    <button type="button" @click="modalOpen = false"
                            class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600
                                   border border-gray-200 hover:bg-gray-50 transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </template>
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