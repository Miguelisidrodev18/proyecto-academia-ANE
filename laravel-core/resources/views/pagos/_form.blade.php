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

    $saldoEdit      = $editing ? (float) $saldoDisponible : 0;
    $matriculasData = $editing ? '[]' : ($matriculasJson ?? '[]');
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-5"
     x-data="{
         matriculaId: '{{ $initialMatricula }}',
         monto: '{{ $initialMonto }}',
         isEditing: {{ $editing ? 'true' : 'false' }},
         saldoEdit: {{ $saldoEdit }},
         matriculas: {{ $matriculasData }},
         get seleccionada() {
             if (this.isEditing) return null;
             return this.matriculas.find(m => m.id == this.matriculaId) ?? null;
         },
         get saldoDisponible() {
             if (this.isEditing) return this.saldoEdit;
             return this.seleccionada ? parseFloat(this.seleccionada.saldo) : 0;
         },
         get montoNumerico() { return parseFloat(this.monto || 0); },
         get excedeSaldo() {
             return this.montoNumerico > 0 && this.saldoDisponible > 0 && this.montoNumerico > this.saldoDisponible;
         }
     }">

    {{-- ── Matrícula ─────────────────────────────────────────────────────── --}}
    @if($editing)
        <div class="md:col-span-2 flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
            <div class="w-10 h-10 rounded-full bg-primary-dark/10 flex items-center justify-center font-bold text-primary-dark flex-shrink-0">
                {{ $pago->matricula->alumno->inicial() }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm">{{ $pago->matricula->alumno->nombreCompleto() }}</p>
                <p class="text-xs text-gray-500">{{ $pago->matricula->plan->nombre }}
                    · DNI: {{ $pago->matricula->alumno->dni }}</p>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-xs text-gray-400">Saldo disponible</p>
                <p class="font-black text-lg text-primary-dark">S/. {{ number_format($saldoDisponible, 2) }}</p>
            </div>
            <input type="hidden" name="matricula_id" value="{{ $pago->matricula_id }}">
        </div>
    @else
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Matrícula <span class="text-red-500">*</span>
            </label>
            <select name="matricula_id"
                    x-model="matriculaId"
                    @class([
                        'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all bg-white',
                        'focus:border-accent focus:ring-2 focus:ring-accent/20',
                        'border-red-400 bg-red-50' => $errors->has('matricula_id'),
                        'border-gray-200'           => !$errors->has('matricula_id'),
                    ])>
                <option value="">— Selecciona una matrícula —</option>
                @foreach(json_decode($matriculasData) as $m)
                    <option value="{{ $m->id }}"
                        {{ (string) $initialMatricula === (string) $m->id ? 'selected' : '' }}>
                        {{ $m->label }}
                    </option>
                @endforeach
            </select>
            @error('matricula_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            {{-- Preview saldo --}}
            <div x-show="seleccionada" x-cloak
                 class="mt-2 grid grid-cols-3 gap-3 p-3 bg-accent/5 border border-accent/20 rounded-xl text-xs">
                <div>
                    <p class="text-gray-400 mb-0.5">Precio plan</p>
                    <p class="font-bold text-gray-700">
                        S/. <span x-text="parseFloat(seleccionada?.precio_pagado ?? 0).toFixed(2)"></span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 mb-0.5">Total pagado</p>
                    <p class="font-bold text-gray-700">
                        S/. <span x-text="parseFloat(seleccionada?.total_pagado ?? 0).toFixed(2)"></span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 mb-0.5">Saldo pendiente</p>
                    <p class="font-black" :class="saldoDisponible > 0 ? 'text-red-600' : 'text-green-600'">
                        S/. <span x-text="saldoDisponible.toFixed(2)"></span>
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- ── Monto ─────────────────────────────────────────────────────────── --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Monto <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">S/.</span>
            <input type="number" name="monto" step="0.01" min="1"
                   x-model="monto"
                   value="{{ $initialMonto }}"
                   placeholder="0.00"
                   @class([
                       'w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm outline-none transition-all',
                       'focus:border-accent focus:ring-2 focus:ring-accent/20',
                       'border-red-400 bg-red-50' => $errors->has('monto'),
                       'border-gray-200'           => !$errors->has('monto'),
                   ])>
        </div>
        @error('monto')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
        {{-- Alerta saldo excedido --}}
        <p x-show="excedeSaldo" x-cloak
           class="text-red-500 text-xs mt-1 font-semibold">
            ⚠ El monto supera el saldo disponible de S/.
            <span x-text="saldoDisponible.toFixed(2)"></span>
        </p>
    </div>

    {{-- ── Método de pago ─────────────────────────────────────────────────── --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Método de pago <span class="text-red-500">*</span>
        </label>
        <select name="metodo_pago"
                @class([
                    'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all bg-white',
                    'focus:border-accent focus:ring-2 focus:ring-accent/20',
                    'border-red-400 bg-red-50' => $errors->has('metodo_pago'),
                    'border-gray-200'           => !$errors->has('metodo_pago'),
                ])>
            @foreach(['efectivo' => 'Efectivo', 'transferencia' => 'Transferencia', 'yape' => 'Yape', 'plin' => 'Plin', 'tarjeta' => 'Tarjeta'] as $val => $label)
                <option value="{{ $val }}" {{ $initialMetodo === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('metodo_pago')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- ── Estado ──────────────────────────────────────────────────────────── --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Estado <span class="text-red-500">*</span>
        </label>
        <select name="estado"
                @class([
                    'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all bg-white',
                    'focus:border-accent focus:ring-2 focus:ring-accent/20',
                    'border-red-400 bg-red-50' => $errors->has('estado'),
                    'border-gray-200'           => !$errors->has('estado'),
                ])>
            @foreach(['confirmado' => 'Confirmado', 'pendiente' => 'Pendiente', 'anulado' => 'Anulado'] as $val => $label)
                <option value="{{ $val }}" {{ $initialEstado === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('estado')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- ── Fecha de pago ───────────────────────────────────────────────────── --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Fecha de pago <span class="text-red-500">*</span>
        </label>
        <input type="date" name="fecha_pago"
               value="{{ $initialFecha }}"
               @class([
                   'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all',
                   'focus:border-accent focus:ring-2 focus:ring-accent/20',
                   'border-red-400 bg-red-50' => $errors->has('fecha_pago'),
                   'border-gray-200'           => !$errors->has('fecha_pago'),
               ])>
        @error('fecha_pago')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- ── Referencia ──────────────────────────────────────────────────────── --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Referencia / Nro. operación
        </label>
        <input type="text" name="referencia"
               value="{{ $initialReferencia }}"
               placeholder="Ej: OP-123456"
               @class([
                   'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all',
                   'focus:border-accent focus:ring-2 focus:ring-accent/20',
                   'border-red-400 bg-red-50' => $errors->has('referencia'),
                   'border-gray-200'           => !$errors->has('referencia'),
               ])>
        @error('referencia')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- ── Comprobante ─────────────────────────────────────────────────────── --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Comprobante
            <span class="text-gray-400 font-normal text-xs">(JPG, PNG o PDF · máx. 2 MB)</span>
        </label>
        @if($editing && $pago->comprobante_url)
            <div class="mb-2 flex items-center gap-2 p-2 bg-gray-50 rounded-xl border border-gray-200 text-xs text-gray-600">
                <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                <a href="{{ Storage::url($pago->comprobante_url) }}" target="_blank"
                   class="truncate text-accent hover:underline">Comprobante adjunto</a>
                <span class="text-gray-400 italic ml-auto">Sube otro para reemplazar</span>
            </div>
        @endif
        <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf"
               @class([
                   'w-full px-4 py-2 rounded-xl border text-sm outline-none transition-all bg-white',
                   'file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0',
                   'file:text-xs file:font-semibold file:bg-primary-dark/10 file:text-primary-dark',
                   'hover:file:bg-primary-dark/20',
                   'border-red-400 bg-red-50' => $errors->has('comprobante'),
                   'border-gray-200'           => !$errors->has('comprobante'),
               ])>
        @error('comprobante')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- ── Notas ───────────────────────────────────────────────────────────── --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-1">Notas</label>
        <textarea name="notas" rows="3"
                  placeholder="Observaciones adicionales sobre este pago..."
                  @class([
                      'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all resize-none',
                      'focus:border-accent focus:ring-2 focus:ring-accent/20',
                      'border-red-400 bg-red-50' => $errors->has('notas'),
                      'border-gray-200'           => !$errors->has('notas'),
                  ])>{{ $initialNotas }}</textarea>
        @error('notas')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>
