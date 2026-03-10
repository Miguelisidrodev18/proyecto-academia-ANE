@extends('layouts.dashboard')
@section('title', 'Matrícula #' . $matricula->id)

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('matriculas.index') }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-gray-300 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-primary-dark">Matrícula #{{ $matricula->id }}</h1>
            <p class="text-gray-400 text-sm">Registrada {{ $matricula->created_at->diffForHumans() }}</p>
        </div>
        <div class="ml-auto flex items-center gap-2">
            @include('matriculas._badge', ['estado' => $matricula->estado])
            <a href="{{ route('matriculas.edit', $matricula) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm
                      border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
        </div>
    </div>

    @include('matriculas._flash')

    {{-- Tarjeta principal --}}
    <div class="bg-gradient-to-br from-primary-dark to-[#30A9D9] rounded-2xl p-6 mb-5 text-white">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Alumno</p>
                <p class="font-black text-lg leading-tight">{{ $matricula->alumno->nombreCompleto() }}</p>
                <p class="text-white/60 text-xs font-mono">DNI: {{ $matricula->alumno->dni }}</p>
            </div>
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Plan</p>
                <p class="font-bold text-lg">{{ $matricula->plan->nombre }}</p>
                <p class="text-white/60 text-xs">{{ $matricula->plan->duracion_meses }} mes(es)</p>
            </div>
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Precio pagado</p>
                <p class="font-black text-2xl">S/. {{ number_format($matricula->precio_pagado, 2) }}</p>
                <p class="text-white/60 text-xs capitalize">
                    {{ match($matricula->tipo_pago) {
                        'mensual' => 'Mensual',
                        'cuotas'  => 'En cuotas',
                        default   => 'Pago completo',
                    } }}
                </p>
            </div>
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Días restantes</p>
                @php $dias = $matricula->diasRestantes(); @endphp
                <p class="font-black text-2xl {{ $dias <= 7 ? 'text-red-300' : ($dias <= 30 ? 'text-yellow-300' : 'text-white') }}">
                    {{ $dias }}
                </p>
                <p class="text-white/60 text-xs">hasta {{ $matricula->fecha_fin?->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Detalle --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

        {{-- Fechas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Fechas</h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Fecha de inicio</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $matricula->fecha_inicio?->format('d/m/Y') ?? '—' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Fecha de fin</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $matricula->fecha_fin?->format('d/m/Y') ?? '—' }}</p>
                    </div>
                </div>
                @if($matricula->dias_cortesia > 0)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Días de cortesía</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $matricula->dias_cortesia }} días extra</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Pagos resumen --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Pagos</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Total pagado</span>
                    <span class="font-black text-lg text-gray-800">S/. {{ number_format($matricula->totalPagado(), 2) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Precio del plan</span>
                    <span class="font-semibold text-gray-700">S/. {{ number_format($matricula->precio_pagado, 2) }}</span>
                </div>
                @php $saldo = $matricula->precio_pagado - $matricula->totalPagado(); @endphp
                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <span class="text-sm font-semibold {{ $saldo > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $saldo > 0 ? 'Saldo pendiente' : 'Al día' }}
                    </span>
                    <span class="font-black {{ $saldo > 0 ? 'text-red-600' : 'text-green-600' }}">
                        S/. {{ number_format(abs($saldo), 2) }}
                    </span>
                </div>
                <a href="#pagos" class="block text-center py-2 rounded-xl text-xs font-semibold
                                        bg-primary-dark/5 text-primary-dark hover:bg-primary-dark/10 transition-colors">
                    Ver historial de pagos ↓
                </a>
            </div>
        </div>
    </div>

    {{-- Observaciones --}}
    @if($matricula->observaciones)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Observaciones</h3>
        <p class="text-sm text-gray-700">{{ $matricula->observaciones }}</p>
    </div>
    @endif

    {{-- Historial de pagos --}}
    <div id="pagos" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Historial de pagos</h3>
            <span class="text-xs text-gray-400">{{ $matricula->pagos->count() }} pago(s)</span>
        </div>

        @if($matricula->pagos->isEmpty())
            <div class="py-8 text-center">
                <p class="text-gray-400 text-sm">Sin pagos registrados aún</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($matricula->pagos->sortByDesc('fecha_pago') as $pago)
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ $pago->estaConfirmado() ? 'bg-green-100 text-green-600' : ($pago->estado === 'anulado' ? 'bg-red-100 text-red-500' : 'bg-yellow-100 text-yellow-600') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">S/. {{ number_format($pago->monto, 2) }}</p>
                            <p class="text-xs text-gray-400">{{ ucfirst($pago->metodo_pago ?? '—') }} · {{ $pago->fecha_pago?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                    </div>
                    @include('pagos._badge', ['estado' => $pago->estado, 'size' => 'sm'])
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection
