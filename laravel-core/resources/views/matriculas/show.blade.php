@extends('layouts.dashboard')
@section('title', 'Matrícula #' . $matricula->id)

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Breadcrumb + acciones --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('matriculas.index') }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('matriculas.index') }}" class="hover:text-accent transition-colors">Matrículas</a>
                <span class="mx-1">/</span>
                <span class="text-gray-600">#{{ $matricula->id }}</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">Matrícula #{{ $matricula->id }}</h1>
        </div>
        <div class="flex items-center gap-2">
            @include('matriculas._badge', ['estado' => $matricula->estado])
            <a href="{{ route('matriculas.edit', $matricula) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-semibold text-sm
                      bg-primary-dark text-white hover:bg-accent transition-all shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
        </div>
    </div>

    @include('matriculas._flash')

    {{-- Hero card --}}
    @php $dias = $matricula->diasRestantes(); $esVip = $matricula->plan->esVip(); @endphp
    <div class="relative rounded-3xl p-6 mb-5 text-white overflow-hidden"
         @if($esVip)
             style="background: linear-gradient(135deg, #1a0a00 0%, #3d1f00 40%, #6b3500 100%);
                    box-shadow: 0 12px 40px rgba(251,191,36,0.25);"
         @else
             style="background: linear-gradient(135deg, #082B59 0%, #0e3d7a 50%, #30A9D9 100%);"
         @endif>
        @if($esVip)
            <div class="absolute inset-0 pointer-events-none"
                 style="background: radial-gradient(ellipse at 85% 0%, rgba(251,191,36,0.2) 0%, transparent 55%),
                                    radial-gradient(ellipse at 5% 100%, rgba(217,119,6,0.12) 0%, transparent 50%);"></div>
            <div class="absolute top-4 right-4">
                <span class="px-3 py-1 rounded-full text-xs font-black bg-gradient-to-r from-amber-400 to-yellow-300 text-amber-900 shadow-lg shadow-amber-400/40">
                    💎 VIP
                </span>
            </div>
        @else
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>
        @endif

        <div class="relative grid grid-cols-2 sm:grid-cols-4 gap-6">
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Alumno</p>
                <p class="font-black text-lg leading-tight">{{ $matricula->alumno->nombreCompleto() }}</p>
                <p class="text-white/50 text-xs font-mono mt-0.5">DNI: {{ $matricula->alumno->dni }}</p>
            </div>
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Plan</p>
                <p class="font-bold text-lg leading-tight {{ $esVip ? 'text-amber-300' : '' }}">
                    {{ $matricula->plan->tipoIcono() }} {{ $matricula->plan->nombre }}
                </p>
                <p class="text-white/50 text-xs mt-0.5">{{ $matricula->plan->duracion_meses }} mes(es)</p>
            </div>
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Precio</p>
                <p class="font-black text-2xl">S/. {{ number_format($matricula->precio_pagado, 2) }}</p>
                <p class="text-white/50 text-xs capitalize">
                    {{ match($matricula->tipo_pago) {
                        'mensual' => 'Mensual',
                        'cuotas'  => 'En cuotas',
                        default   => 'Pago completo',
                    } }}
                </p>
            </div>
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Días restantes</p>
                <p class="font-black text-3xl {{ $dias <= 0 ? 'text-red-300' : ($dias <= 7 ? 'text-orange-300' : ($dias <= 30 ? 'text-yellow-300' : 'text-white')) }}">
                    {{ max(0, $dias) }}
                </p>
                <p class="text-white/50 text-xs mt-0.5">hasta {{ $matricula->fecha_fin?->format('d/m/Y') ?? '—' }}</p>
                @if($dias > 0 && $dias <= 7)
                    <p class="text-orange-300 text-xs font-bold mt-0.5">⚠️ Por vencer</p>
                @elseif($dias <= 0)
                    <p class="text-red-300 text-xs font-bold mt-0.5">🔴 Vencida</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Detalle --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

        {{-- Fechas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-accent/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                Vigencia
            </h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Fecha de inicio</p>
                        <p class="text-sm font-bold text-gray-700">{{ $matricula->fecha_inicio?->format('d/m/Y') ?? '—' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Fecha de fin</p>
                        <p class="text-sm font-bold text-gray-700">{{ $matricula->fecha_fin?->format('d/m/Y') ?? '—' }}</p>
                    </div>
                </div>
                @if($matricula->dias_cortesia > 0)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-amber-50 border border-amber-100">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-amber-600">Días de cortesía</p>
                        <p class="text-sm font-bold text-amber-700">+{{ $matricula->dias_cortesia }} días extra</p>
                    </div>
                </div>
                @endif
                <p class="text-xs text-gray-400 mt-1">Registrada {{ $matricula->created_at->diffForHumans() }}</p>
            </div>
        </div>

        {{-- Pagos resumen --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-primary-dark/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                Resumen de pagos
            </h3>
            @php
                $totalPagado = $matricula->totalPagado();
                $saldo = $matricula->precio_pagado - $totalPagado;
                $porcentaje = $matricula->precio_pagado > 0 ? min(100, ($totalPagado / $matricula->precio_pagado) * 100) : 0;
            @endphp
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Total pagado</span>
                    <span class="font-black text-xl text-gray-800">S/. {{ number_format($totalPagado, 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Precio del plan</span>
                    <span class="font-semibold text-gray-600">S/. {{ number_format($matricula->precio_pagado, 2) }}</span>
                </div>
                {{-- Barra de progreso --}}
                <div>
                    <div class="flex justify-between text-xs text-gray-400 mb-1">
                        <span>Progreso de pago</span>
                        <span>{{ number_format($porcentaje, 0) }}%</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500
                                    {{ $porcentaje >= 100 ? 'bg-gradient-to-r from-emerald-400 to-green-500' : 'bg-gradient-to-r from-accent to-primary-light' }}"
                             style="width: {{ $porcentaje }}%"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <span class="text-sm font-bold {{ $saldo > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                        {{ $saldo > 0 ? '⚠️ Saldo pendiente' : '✅ Al día' }}
                    </span>
                    <span class="font-black {{ $saldo > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                        S/. {{ number_format(abs($saldo), 2) }}
                    </span>
                </div>
                <a href="#pagos" class="flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold
                                        bg-primary-dark/5 text-primary-dark hover:bg-primary-dark/10 transition-colors">
                    Ver historial de pagos
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Observaciones --}}
    @if($matricula->observaciones)
    <div class="bg-amber-50 rounded-2xl border border-amber-100 p-5 mb-4">
        <h3 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-2 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Observaciones
        </h3>
        <p class="text-sm text-amber-800">{{ $matricula->observaciones }}</p>
    </div>
    @endif

    {{-- Cuotas timeline --}}
    @if($matricula->cuotas->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-5 h-5 rounded-md bg-teal-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </span>
            Cuotas
            <span class="text-xs bg-gray-100 text-gray-600 font-semibold px-2 py-0.5 rounded-full">{{ $matricula->cuotas->count() }}</span>
        </h3>
        <div class="space-y-2">
            @foreach($matricula->cuotas as $cuota)
            @php
                $badgeClass = match($cuota->estado) {
                    'pagada'   => 'bg-emerald-100 text-emerald-700',
                    'vencida'  => 'bg-red-100 text-red-700',
                    default    => 'bg-amber-100 text-amber-700',
                };
                $rowClass = match($cuota->estado) {
                    'pagada'   => 'border-emerald-100 bg-emerald-50',
                    'vencida'  => 'border-red-100 bg-red-50',
                    default    => 'border-gray-100 bg-gray-50',
                };
                $numClass = match($cuota->estado) {
                    'pagada'   => 'bg-emerald-200 text-emerald-700',
                    'vencida'  => 'bg-red-200 text-red-700',
                    default    => 'bg-amber-100 text-amber-700',
                };
            @endphp
            <div class="flex items-center gap-3 p-3.5 rounded-xl border {{ $rowClass }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 font-black text-sm {{ $numClass }}">
                    #{{ $cuota->numero }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="text-sm font-bold text-gray-700">Cuota {{ $cuota->numero }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full font-bold uppercase {{ $badgeClass }}">
                            {{ $cuota->estado }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Vence: {{ $cuota->fecha_vencimiento->format('d/m/Y') }}
                        @if($cuota->fecha_pago)
                            · Pagada: {{ $cuota->fecha_pago->format('d/m/Y') }}
                        @endif
                    </p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-base font-black text-gray-800">S/. {{ number_format($cuota->monto, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @php
            $cuotasPagadas = $matricula->cuotas->where('estado', 'pagada')->count();
            $totalCuotas   = $matricula->cuotas->count();
        @endphp
        <div class="mt-3 flex items-center gap-3">
            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-teal-400 to-emerald-500 rounded-full"
                     style="width: {{ $totalCuotas > 0 ? ($cuotasPagadas / $totalCuotas * 100) : 0 }}%"></div>
            </div>
            <span class="text-xs text-gray-500 font-semibold flex-shrink-0">{{ $cuotasPagadas }}/{{ $totalCuotas }} pagadas</span>
        </div>
    </div>
    @endif

    {{-- Historial de pagos --}}
    <div id="pagos" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-primary-dark/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </span>
                Historial de pagos
            </h3>
            <div class="flex items-center gap-2">
                <span class="text-xs bg-gray-100 text-gray-600 font-semibold px-2 py-0.5 rounded-full">{{ $matricula->pagos->count() }} pago(s)</span>
                <a href="{{ route('pagos.create', ['matricula_id' => $matricula->id]) }}"
                   class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-lg bg-accent/10 text-accent hover:bg-accent hover:text-white transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Agregar pago
                </a>
            </div>
        </div>

        @if($matricula->pagos->isEmpty())
            <div class="py-10 text-center">
                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-gray-400 text-sm font-medium">Sin pagos registrados aún</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($matricula->pagos->sortByDesc('fecha_pago') as $pago)
                <a href="{{ route('pagos.show', $pago) }}"
                   class="flex items-center justify-between p-3.5 rounded-xl border transition-all duration-150
                          {{ $pago->estaConfirmado() ? 'border-emerald-100 bg-emerald-50 hover:bg-emerald-100' : ($pago->estado === 'anulado' ? 'border-red-100 bg-red-50 hover:bg-red-100' : 'border-yellow-100 bg-yellow-50 hover:bg-yellow-100') }}">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 font-bold
                                    {{ $pago->estaConfirmado() ? 'bg-emerald-100 text-emerald-600' : ($pago->estado === 'anulado' ? 'bg-red-100 text-red-500' : 'bg-yellow-100 text-yellow-600') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-black text-gray-700">S/. {{ number_format($pago->monto, 2) }}</p>
                            <p class="text-xs text-gray-400">{{ ucfirst($pago->metodo_pago ?? '—') }} · {{ $pago->fecha_pago?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @include('pagos._badge', ['estado' => $pago->estado, 'size' => 'sm'])
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection
