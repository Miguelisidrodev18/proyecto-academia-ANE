@extends('layouts.dashboard')
@section('title', 'Pago #' . $pago->id)

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Breadcrumb + acciones --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('pagos.index') }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('pagos.index') }}" class="hover:text-accent transition-colors">Pagos</a>
                <span class="mx-1">/</span>
                <span class="text-gray-600">#{{ $pago->id }}</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">Pago #{{ $pago->id }}</h1>
        </div>
        <div class="flex items-center gap-2">
            @include('pagos._badge', ['estado' => $pago->estado])
            @if($pago->estado !== 'anulado')
                <a href="{{ route('pagos.edit', $pago) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-semibold text-sm
                          bg-primary-dark text-white hover:bg-accent transition-all shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
                <form method="POST" action="{{ route('pagos.destroy', $pago) }}"
                      onsubmit="return confirm('¿Anular este pago? No se puede deshacer.')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-semibold text-sm
                                   border border-red-200 text-red-500 hover:bg-red-50 hover:border-red-300 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Anular
                    </button>
                </form>
            @endif
        </div>
    </div>

    @include('pagos._flash')

    {{-- Banner de flujo completo --}}
    @if(session('flow_complete'))
    <div class="flex items-center gap-4 p-5 mb-5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl shadow-md text-white">
        <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-black text-base leading-tight">¡Registro completo!</p>
            <p class="text-emerald-100 text-sm mt-0.5">Alumno, matrícula y pago registrados correctamente en 3 pasos.</p>
        </div>
        <div class="flex gap-2 flex-shrink-0">
            <a href="{{ route('alumnos.create') }}"
               class="px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30 font-bold text-sm transition-colors">
                + Nuevo alumno
            </a>
            <a href="{{ route('alumnos.show', $pago->matricula->alumno) }}"
               class="px-4 py-2 rounded-xl bg-white text-emerald-700 hover:bg-emerald-50 font-bold text-sm transition-colors">
                Ver alumno
            </a>
        </div>
    </div>
    @endif

    {{-- Stepper completado --}}
    @if(session('flow_complete'))
        @include('partials.flow-stepper', [
            'flowStep'      => 4,
            'flowAlumno'    => $pago->matricula->alumno,
            'flowMatricula' => $pago->matricula,
        ])
    @endif

    {{-- Hero card --}}
    <div class="relative bg-gradient-to-br from-primary-dark via-[#0e3d7a] to-[#30A9D9] rounded-3xl p-6 mb-5 text-white overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

        <div class="relative grid grid-cols-2 sm:grid-cols-4 gap-6">
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Monto</p>
                <p class="font-black text-3xl">S/. {{ number_format($pago->monto, 2) }}</p>
            </div>
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Método</p>
                @php
                    $icon = match($pago->metodo_pago) {
                        'yape' => '📱', 'plin' => '📲', 'transferencia' => '🏦',
                        'tarjeta' => '💳', default => '💵',
                    };
                @endphp
                <p class="font-bold text-xl capitalize">{{ $icon }} {{ $pago->metodo_pago }}</p>
            </div>
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Fecha</p>
                <p class="font-bold text-xl">{{ $pago->fecha_pago?->format('d/m/Y') ?? '—' }}</p>
            </div>
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Referencia</p>
                <p class="font-bold text-xl font-mono">{{ $pago->referencia ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Grid detalle --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

        {{-- Matrícula y alumno --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-accent/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                    </svg>
                </span>
                Matrícula
            </h3>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 mb-4">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center
                            font-black text-white flex-shrink-0 shadow-sm">
                    {{ $pago->matricula->alumno->inicial() }}
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $pago->matricula->alumno->nombreCompleto() }}</p>
                    <p class="text-xs text-gray-400 font-mono">DNI: {{ $pago->matricula->alumno->dni }}</p>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-500">Plan</span>
                    <span class="font-semibold text-gray-700">{{ $pago->matricula->plan->nombre }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-500">Precio plan</span>
                    <span class="font-semibold text-gray-700 font-mono">S/. {{ number_format($pago->matricula->precio_pagado, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-500">Matrícula #</span>
                    <a href="{{ route('matriculas.show', $pago->matricula) }}"
                       class="font-bold text-accent hover:underline flex items-center gap-1">
                        {{ $pago->matricula_id }}
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Resumen de saldo --}}
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
                $totalPagado = (float) $pago->matricula->pagos->where('estado', 'confirmado')->sum('monto');
                $saldo       = $pago->matricula->precio_pagado - $totalPagado;
                $porcentaje  = $pago->matricula->precio_pagado > 0 ? min(100, ($totalPagado / $pago->matricula->precio_pagado) * 100) : 0;
            @endphp
            <div class="space-y-3">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Total confirmado</span>
                    <span class="font-black text-xl text-gray-800">S/. {{ number_format($totalPagado, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Precio plan</span>
                    <span class="font-semibold text-gray-600 font-mono">S/. {{ number_format($pago->matricula->precio_pagado, 2) }}</span>
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
                <div class="flex justify-between items-center pt-2 border-t border-gray-100 text-sm">
                    <span class="font-bold {{ $saldo > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                        {{ $saldo > 0 ? '⚠️ Saldo pendiente' : '✅ Al día' }}
                    </span>
                    <span class="font-black {{ $saldo > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                        S/. {{ number_format(abs($saldo), 2) }}
                    </span>
                </div>
                <a href="{{ route('matriculas.show', $pago->matricula) }}"
                   class="flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold
                          bg-primary-dark/5 text-primary-dark hover:bg-primary-dark/10 transition-colors">
                    Ver matrícula completa
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Notas y comprobante --}}
    @if($pago->notas || $pago->comprobante_url)
    <div class="grid grid-cols-1 {{ $pago->notas && $pago->comprobante_url ? 'md:grid-cols-2' : '' }} gap-4 mb-5">

        @if($pago->notas)
        <div class="bg-amber-50 rounded-2xl border border-amber-100 p-5">
            <h3 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Notas
            </h3>
            <p class="text-sm text-amber-800">{{ $pago->notas }}</p>
        </div>
        @endif

        @if($pago->comprobante_url)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                Comprobante
            </h3>
            @php $ext = pathinfo($pago->comprobante_url, PATHINFO_EXTENSION); @endphp
            @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                <img src="{{ Storage::url($pago->comprobante_url) }}"
                     alt="Comprobante"
                     class="w-full max-h-52 object-contain rounded-xl border border-gray-100 bg-gray-50">
            @else
                <a href="{{ Storage::url($pago->comprobante_url) }}" target="_blank"
                   class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-100 hover:bg-accent/5 hover:border-accent/20 transition-all text-sm text-accent font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Ver comprobante PDF
                </a>
            @endif
        </div>
        @endif

    </div>
    @endif

    {{-- Footer: registrado por --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center gap-3 text-sm text-gray-500">
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <span>
                Registrado por
                <span class="font-bold text-gray-700">{{ $pago->user?->name ?? 'Sistema' }}</span>
                el <span class="font-medium">{{ $pago->created_at->format('d/m/Y H:i') }}</span>
            </span>
        </div>
    </div>

</div>

@endsection
