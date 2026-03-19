{{-- Partial compartido por create y edit --}}
{{-- Variables create: $matriculasJson, $matriculaSeleccionada --}}
{{-- Variables edit:   $pago, $saldoDisponible --}}

@php
    $editing           = isset($pago);
    $initialMonto      = old('monto',       $editing ? (float) $pago->monto       : '');
    $initialMatricula  = old('matricula_id', $editing ? $pago->matricula_id        : ($matriculaSeleccionada?->id ?? ''));
    $initialMetodo     = old('metodo_pago', $editing ? $pago->metodo_pago         : 'efectivo');
    $initialEstado     = old('estado',      $editing ? $pago->estado              : 'pendiente');
    $initialFecha      = old('fecha_pago',  $editing ? $pago->fecha_pago?->format('Y-m-d') : date('Y-m-d'));
    $initialReferencia = old('referencia',  $editing ? ($pago->referencia ?? '')  : '');
    $initialNotas      = old('notas',       $editing ? ($pago->notas ?? '')       : '');
    $initialCuota      = old('cuota_id',   $editing ? ($pago->cuota_id ?? '')    : '');

    $saldoEdit      = $editing ? (float) $saldoDisponible : 0;
    $matriculasData = $editing ? '[]' : ($matriculasJson ?? '[]');

    // Label inicial para búsqueda (sólo en create con matricula pre-seleccionada)
    $initialLabel = '';
    if (!$editing && isset($matriculaSeleccionada) && $matriculaSeleccionada) {
        $initialLabel = $matriculaSeleccionada->alumno->nombreCompleto() . ' — ' . $matriculaSeleccionada->plan->nombre . ' (DNI: ' . $matriculaSeleccionada->alumno->dni . ')';
    }
@endphp

{{-- Alpine root --}}
<div x-data="{
        editing: {{ $editing ? 'true' : 'false' }},
        saldoEdit: {{ $saldoEdit }},
        matriculas: {{ $matriculasData }},

        /* ── searchable dropdown ── */
        open: false,
        search: '',
        selected: {
            id: '{{ $initialMatricula }}',
            label: '{{ addslashes($initialLabel) }}',
            precio_pagado: 0,
            total_pagado: 0,
            saldo: 0
        },
        get filtered() {
            if (!this.search) return this.matriculas;
            const s = this.search.toLowerCase();
            return this.matriculas.filter(m => m.label.toLowerCase().includes(s));
        },
        selectOption(m) {
            this.selected = m;
            this.open = false;
            this.search = '';
        },

        /* ── saldo logic ── */
        monto: '{{ $initialMonto }}',
        get saldoDisponible() {
            if (this.editing) return this.saldoEdit;
            return this.selected.id ? parseFloat(this.selected.saldo || 0) : 0;
        },
        get montoNum() { return parseFloat(this.monto || 0); },
        get excedeSaldo() {
            return this.montoNum > 0 && this.saldoDisponible > 0 && this.montoNum > this.saldoDisponible;
        },

        /* ── cuota selector ── */
        cuota_id: '{{ $initialCuota }}',
        get cuotasPendientes() {
            if (!this.selected.id || !this.selected.cuotas) return [];
            return this.selected.cuotas;
        },

        /* ── metodo & estado ── */
        metodo: '{{ $initialMetodo }}',
        estado: '{{ $initialEstado }}'
     }"
     @click.outside="open = false"
     class="space-y-5">

    {{-- ══════════════════════════════════════════
         CARD 1 · Matrícula
    ══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-3.5 bg-gradient-to-r from-primary-dark to-primary-light">
            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white text-sm">🎓</div>
            <h3 class="font-bold text-white text-sm tracking-wide">Matrícula</h3>
        </div>
        <div class="p-5">
            @if($editing)
                {{-- Modo edición: tarjeta readonly --}}
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="w-11 h-11 rounded-full bg-primary-dark flex items-center justify-center font-black text-white text-base flex-shrink-0">
                        {{ $pago->matricula->alumno->inicial() }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-800 text-sm leading-tight">{{ $pago->matricula->alumno->nombreCompleto() }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $pago->matricula->plan->nombre }} · DNI: {{ $pago->matricula->alumno->dni }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold">Saldo disponible</p>
                        <p class="font-black text-xl text-primary-dark leading-tight">S/. {{ number_format($saldoDisponible, 2) }}</p>
                    </div>
                    <input type="hidden" name="matricula_id" value="{{ $pago->matricula_id }}">
                </div>
            @else
                {{-- Modo crear: searchable dropdown --}}
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Buscar alumno / matrícula <span class="text-red-500">*</span>
                </label>
                <div class="relative" x-data>
                    <input type="hidden" name="matricula_id" :value="selected.id">

                    {{-- Input de búsqueda --}}
                    <div class="relative"
                         @click="open = !open">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text"
                               x-model="search"
                               @focus="open = true"
                               :placeholder="selected.id ? selected.label : 'Buscar por nombre o DNI...'"
                               class="w-full pl-9 pr-10 py-3 rounded-xl border text-sm outline-none transition-all
                                      @error('matricula_id') border-red-400 bg-red-50 @else border-gray-200 @enderror
                                      focus:border-accent focus:ring-2 focus:ring-accent/20 cursor-pointer">
                        {{-- Chevron --}}
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition-transform duration-200"
                             :class="open ? 'rotate-180' : ''">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Dropdown --}}
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute z-30 w-full mt-1 bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden">
                        {{-- Lista --}}
                        <ul class="max-h-56 overflow-y-auto divide-y divide-gray-50">
                            <template x-if="filtered.length === 0">
                                <li class="px-4 py-6 text-center text-sm text-gray-400">
                                    No se encontraron matrículas activas
                                </li>
                            </template>
                            <template x-for="m in filtered" :key="m.id">
                                <li @click="selectOption(m)"
                                    class="flex items-center justify-between gap-3 px-4 py-3 cursor-pointer hover:bg-accent/5 transition-colors"
                                    :class="selected.id == m.id ? 'bg-accent/10' : ''">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate" x-text="m.label"></p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-xs text-gray-400">Saldo</p>
                                        <p class="text-sm font-black" :class="parseFloat(m.saldo) > 0 ? 'text-red-500' : 'text-green-600'"
                                           x-text="'S/. ' + parseFloat(m.saldo).toFixed(2)"></p>
                                    </div>
                                    <template x-if="selected.id == m.id">
                                        <svg class="w-4 h-4 text-accent flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </template>
                                </li>
                            </template>
                        </ul>
                        {{-- Footer info --}}
                        <div x-show="filtered.length > 0"
                             class="px-4 py-2 bg-gray-50 border-t border-gray-100 text-[11px] text-gray-400 flex items-center gap-1">
                            <span x-text="filtered.length"></span> matrícula(s) disponible(s) con saldo pendiente
                        </div>
                    </div>
                </div>

                @error('matricula_id')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror

                {{-- Preview saldo de la seleccionada --}}
                <div x-show="selected.id" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="mt-3 grid grid-cols-3 gap-3 p-4 bg-gradient-to-r from-accent/5 to-primary-light/5 border border-accent/20 rounded-xl">
                    <div class="text-center">
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-1">Precio plan</p>
                        <p class="font-black text-gray-800">S/. <span x-text="parseFloat(selected.precio_pagado || 0).toFixed(2)"></span></p>
                    </div>
                    <div class="text-center border-x border-accent/20">
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-1">Ya pagado</p>
                        <p class="font-black text-green-600">S/. <span x-text="parseFloat(selected.total_pagado || 0).toFixed(2)"></span></p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-1">Saldo</p>
                        <p class="font-black" :class="saldoDisponible > 0 ? 'text-red-600' : 'text-green-600'">
                            S/. <span x-text="saldoDisponible.toFixed(2)"></span>
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         CARD 1b · Cuota (opcional)
    ══════════════════════════════════════════ --}}
    @if(!$editing)
    <div x-show="cuotasPendientes.length > 0" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-3.5 bg-gradient-to-r from-teal-600 to-cyan-500">
            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white text-sm">📅</div>
            <h3 class="font-bold text-white text-sm tracking-wide">Cuota a pagar <span class="font-normal opacity-70 text-xs">(opcional)</span></h3>
        </div>
        <div class="p-5">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                Asociar a una cuota específica
            </label>
            <div class="space-y-2">
                {{-- Opción "Sin cuota" --}}
                <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                       :class="cuota_id === '' ? 'border-accent bg-accent/5' : 'border-gray-200 hover:border-gray-300'">
                    <input type="radio" name="cuota_id" value=""
                           x-model="cuota_id"
                           class="sr-only">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-400 text-sm font-bold">—</div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-700">Sin cuota específica</p>
                        <p class="text-xs text-gray-400">Pago libre / parcial</p>
                    </div>
                    <div x-show="cuota_id === ''"
                         class="w-5 h-5 rounded-full bg-accent flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </label>
                {{-- Cuotas pendientes --}}
                <template x-for="c in cuotasPendientes" :key="c.id">
                    <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                           :class="cuota_id == c.id ? 'border-teal-400 bg-teal-50' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" name="cuota_id" :value="c.id"
                               x-model="cuota_id"
                               class="sr-only">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-sm font-black"
                             :class="c.estado === 'vencida' ? 'bg-red-100 text-red-600' : 'bg-teal-100 text-teal-700'"
                             x-text="'#' + c.numero"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-700">
                                Cuota <span x-text="c.numero"></span>
                                <span class="ml-1 text-xs px-1.5 py-0.5 rounded-md font-bold uppercase"
                                      :class="c.estado === 'vencida' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-700'"
                                      x-text="c.estado"></span>
                            </p>
                            <p class="text-xs text-gray-400">Vence: <span x-text="c.fecha_vencimiento"></span></p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-black text-gray-800">S/. <span x-text="c.monto.toFixed(2)"></span></p>
                        </div>
                        <div x-show="cuota_id == c.id"
                             class="w-5 h-5 rounded-full bg-teal-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </label>
                </template>
            </div>
            @error('cuota_id')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>
    @else
        {{-- Edit mode: show linked cuota if any --}}
        @if($pago->cuota_id)
        <div class="bg-teal-50 rounded-2xl border border-teal-100 p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-teal-100 flex items-center justify-center flex-shrink-0 font-black text-teal-700">
                #{{ $pago->cuota->numero ?? '?' }}
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-teal-800">Cuota #{{ $pago->cuota->numero ?? '?' }} asociada</p>
                <p class="text-xs text-teal-600">Vence: {{ $pago->cuota?->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</p>
            </div>
            <input type="hidden" name="cuota_id" value="{{ $pago->cuota_id }}">
        </div>
        @endif
    @endif

    {{-- ══════════════════════════════════════════
         CARD 2 · Monto y Fecha
    ══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-500">
            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white text-sm">💰</div>
            <h3 class="font-bold text-white text-sm tracking-wide">Monto y Fecha</h3>
        </div>
        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Monto --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Monto <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-primary-dark font-black text-sm pointer-events-none">S/.</span>
                    <input type="number" name="monto" step="0.01" min="1"
                           x-model="monto"
                           value="{{ $initialMonto }}"
                           placeholder="0.00"
                           class="w-full pl-12 pr-4 py-3 rounded-xl border text-sm font-semibold outline-none transition-all
                                  @error('monto') border-red-400 bg-red-50 @else border-gray-200 @enderror
                                  focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>
                @error('monto')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
                {{-- Alerta saldo excedido --}}
                <div x-show="excedeSaldo" x-cloak
                     class="mt-2 flex items-center gap-1.5 text-red-600 text-xs font-semibold bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                    <span>⚠️</span>
                    <span>Supera el saldo disponible de S/. <span x-text="saldoDisponible.toFixed(2)"></span></span>
                </div>
                {{-- Hint saldo --}}
                <div x-show="saldoDisponible > 0 && !excedeSaldo" x-cloak
                     class="mt-1.5 text-xs text-gray-400">
                    Saldo máximo: <span class="font-semibold text-primary-dark">S/. <span x-text="saldoDisponible.toFixed(2)"></span></span>
                </div>
            </div>

            {{-- Fecha de pago --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Fecha de pago <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input type="date" name="fecha_pago"
                           value="{{ $initialFecha }}"
                           class="w-full pl-10 pr-4 py-3 rounded-xl border text-sm outline-none transition-all
                                  @error('fecha_pago') border-red-400 bg-red-50 @else border-gray-200 @enderror
                                  focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>
                @error('fecha_pago')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════
         CARD 3 · Método de pago
    ══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-3.5 bg-gradient-to-r from-violet-600 to-purple-500">
            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white text-sm">💳</div>
            <h3 class="font-bold text-white text-sm tracking-wide">Método de Pago</h3>
        </div>
        <div class="p-5">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                Selecciona cómo se realizó el pago <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-3 sm:grid-cols-6 gap-2.5">
                @php
                    $metodos = [
                        'efectivo'      => ['emoji' => '💵', 'label' => 'Efectivo',      'color' => 'from-green-500 to-emerald-400'],
                        'yape'          => ['emoji' => '📱', 'label' => 'Yape',          'color' => 'from-purple-600 to-violet-500'],
                        'plin'          => ['emoji' => '📲', 'label' => 'Plin',          'color' => 'from-sky-500 to-blue-400'],
                        'transferencia' => ['emoji' => '🏦', 'label' => 'Transferencia', 'color' => 'from-blue-600 to-indigo-500'],
                        'tarjeta'       => ['emoji' => '💳', 'label' => 'Tarjeta',       'color' => 'from-orange-500 to-amber-400'],
                        'mixto'         => ['emoji' => '🔄', 'label' => 'Mixto',         'color' => 'from-gray-600 to-gray-500'],
                    ];
                @endphp
                @foreach($metodos as $val => $info)
                    <label class="cursor-pointer group">
                        <input type="radio" name="metodo_pago" value="{{ $val }}"
                               x-model="metodo"
                               {{ $initialMetodo === $val ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="relative flex flex-col items-center justify-center gap-1.5 p-3 rounded-xl border-2 transition-all duration-200
                                    border-gray-200 bg-gray-50 text-gray-500
                                    peer-checked:border-transparent peer-checked:text-white peer-checked:shadow-lg
                                    peer-checked:bg-gradient-to-br peer-checked:{{ $info['color'] }}
                                    hover:border-gray-300 hover:bg-white group-hover:scale-[1.03]">
                            <span class="text-xl leading-none">{{ $info['emoji'] }}</span>
                            <span class="text-[11px] font-bold leading-tight text-center">{{ $info['label'] }}</span>
                            {{-- Check badge --}}
                            <div class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-white rounded-full shadow flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity scale-0 peer-checked:scale-100"
                                 style="display:none"
                                 x-show="metodo === '{{ $val }}'">
                                <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('metodo_pago')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         CARD 4 · Estado
    ══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-3.5 bg-gradient-to-r from-amber-500 to-orange-400">
            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white text-sm">📋</div>
            <h3 class="font-bold text-white text-sm tracking-wide">Estado del Pago</h3>
        </div>
        <div class="p-5">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                Estado actual <span class="text-red-500">*</span>
            </label>
            <div class="flex flex-wrap gap-3">
                @php
                    $estados = [
                        'confirmado' => ['emoji' => '✅', 'label' => 'Confirmado', 'ring' => 'peer-checked:bg-green-500 peer-checked:border-green-500'],
                        'pendiente'  => ['emoji' => '🟡', 'label' => 'Pendiente',  'ring' => 'peer-checked:bg-amber-500 peer-checked:border-amber-500'],
                        'anulado'    => ['emoji' => '❌', 'label' => 'Anulado',    'ring' => 'peer-checked:bg-red-500 peer-checked:border-red-500'],
                    ];
                @endphp
                @foreach($estados as $val => $info)
                    <label class="cursor-pointer flex-1 min-w-[120px]">
                        <input type="radio" name="estado" value="{{ $val }}"
                               x-model="estado"
                               {{ $initialEstado === $val ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl border-2 transition-all duration-200
                                    border-gray-200 bg-white text-gray-600 font-semibold text-sm
                                    peer-checked:text-white peer-checked:shadow-md {{ $info['ring'] }}
                                    hover:border-gray-300 cursor-pointer select-none">
                            <span>{{ $info['emoji'] }}</span>
                            <span>{{ $info['label'] }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('estado')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         CARD 5 · Detalles adicionales
    ══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-3.5 bg-gradient-to-r from-gray-700 to-gray-600">
            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white text-sm">📎</div>
            <h3 class="font-bold text-white text-sm tracking-wide">Detalles Adicionales</h3>
        </div>
        <div class="p-5 space-y-5">

            {{-- Referencia --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Referencia / Nro. operación
                </label>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                    </div>
                    <input type="text" name="referencia"
                           value="{{ $initialReferencia }}"
                           placeholder="Ej: OP-123456, TXN-ABCDEF..."
                           class="w-full pl-10 pr-4 py-3 rounded-xl border text-sm outline-none transition-all
                                  @error('referencia') border-red-400 bg-red-50 @else border-gray-200 @enderror
                                  focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>
                @error('referencia')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Comprobante --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Comprobante
                    <span class="text-gray-400 font-normal text-xs normal-case ml-1">(JPG, PNG o PDF · máx. 2 MB)</span>
                </label>
                @if($editing && $pago->comprobante_url)
                    <div class="mb-3 flex items-center gap-3 p-3 bg-accent/5 border border-accent/20 rounded-xl">
                        <div class="w-9 h-9 rounded-xl bg-accent/10 flex items-center justify-center text-accent flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ Storage::url($pago->comprobante_url) }}" target="_blank"
                               class="text-sm font-semibold text-accent hover:underline truncate block">
                                Ver comprobante actual
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">Sube un nuevo archivo para reemplazarlo</p>
                        </div>
                    </div>
                @endif
                <div class="relative">
                    <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all bg-white
                                  file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0
                                  file:text-xs file:font-bold file:bg-primary-dark/10 file:text-primary-dark
                                  hover:file:bg-primary-dark/20 file:cursor-pointer file:transition-colors
                                  @error('comprobante') border-red-400 bg-red-50 @else border-gray-200 @enderror
                                  focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>
                @error('comprobante')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notas --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Notas</label>
                <textarea name="notas" rows="3"
                          placeholder="Observaciones adicionales sobre este pago..."
                          class="w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all resize-none
                                 @error('notas') border-red-400 bg-red-50 @else border-gray-200 @enderror
                                 focus:border-accent focus:ring-2 focus:ring-accent/20">{{ $initialNotas }}</textarea>
                @error('notas')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>

</div>