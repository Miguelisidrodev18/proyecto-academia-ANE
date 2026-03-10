@extends('layouts.dashboard')
@section('title', 'Pago #' . $pago->id)

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('pagos.index') }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-gray-300 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-primary-dark">Pago #{{ $pago->id }}</h1>
            <p class="text-gray-400 text-sm">Registrado {{ $pago->created_at->diffForHumans() }}</p>
        </div>
        <div class="ml-auto flex items-center gap-2">
            @include('pagos._badge', ['estado' => $pago->estado])
            @if($pago->estado !== 'anulado')
                <a href="{{ route('pagos.edit', $pago) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm
                          border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
                <form method="POST" action="{{ route('pagos.destroy', $pago) }}"
                      onsubmit="return confirm('¿Anular este pago? No se puede deshacer.')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm
                                   border border-red-200 text-red-500 hover:bg-red-50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    {{-- Tarjeta principal --}}
    <div class="bg-gradient-to-br from-primary-dark to-[#30A9D9] rounded-2xl p-6 mb-5 text-white">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Monto</p>
                <p class="font-black text-3xl">S/. {{ number_format($pago->monto, 2) }}</p>
            </div>
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Método</p>
                <p class="font-bold text-lg capitalize">{{ $pago->metodo_pago }}</p>
            </div>
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Fecha</p>
                <p class="font-bold text-lg">{{ $pago->fecha_pago?->format('d/m/Y') ?? '—' }}</p>
            </div>
            <div>
                <p class="text-white/50 text-xs uppercase tracking-wide mb-1">Referencia</p>
                <p class="font-bold text-lg font-mono">{{ $pago->referencia ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Grid detalle --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

        {{-- Matrícula y alumno --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Matrícula</h3>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-primary-dark/10 flex items-center justify-center
                            font-bold text-primary-dark flex-shrink-0">
                    {{ $pago->matricula->alumno->inicial() }}
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $pago->matricula->alumno->nombreCompleto() }}</p>
                    <p class="text-xs text-gray-400 font-mono">DNI: {{ $pago->matricula->alumno->dni }}</p>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Plan</span>
                    <span class="font-semibold text-gray-700">{{ $pago->matricula->plan->nombre }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Precio plan</span>
                    <span class="font-semibold text-gray-700">S/. {{ number_format($pago->matricula->precio_pagado, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Matrícula #</span>
                    <a href="{{ route('matriculas.show', $pago->matricula) }}"
                       class="font-semibold text-accent hover:underline">
                        {{ $pago->matricula_id }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Resumen de saldo --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Resumen de pagos</h3>
            @php
                $totalPagado   = (float) $pago->matricula->pagos->where('estado', 'confirmado')->sum('monto');
                $saldo         = $pago->matricula->precio_pagado - $totalPagado;
            @endphp
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Total confirmado</span>
                    <span class="font-black text-lg text-gray-800">S/. {{ number_format($totalPagado, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Precio plan</span>
                    <span class="font-semibold text-gray-700">S/. {{ number_format($pago->matricula->precio_pagado, 2) }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-100 text-sm">
                    <span class="font-semibold {{ $saldo > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $saldo > 0 ? 'Saldo pendiente' : 'Al día' }}
                    </span>
                    <span class="font-black {{ $saldo > 0 ? 'text-red-600' : 'text-green-600' }}">
                        S/. {{ number_format(abs($saldo), 2) }}
                    </span>
                </div>
                <a href="{{ route('matriculas.show', $pago->matricula) }}"
                   class="block text-center py-2 rounded-xl text-xs font-semibold
                          bg-primary-dark/5 text-primary-dark hover:bg-primary-dark/10 transition-colors">
                    Ver matrícula completa →
                </a>
            </div>
        </div>
    </div>

    {{-- Notas y comprobante --}}
    @if($pago->notas || $pago->comprobante_url)
    <div class="grid grid-cols-1 {{ $pago->notas && $pago->comprobante_url ? 'md:grid-cols-2' : '' }} gap-4 mb-5">

        @if($pago->notas)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Notas</h3>
            <p class="text-sm text-gray-700">{{ $pago->notas }}</p>
        </div>
        @endif

        @if($pago->comprobante_url)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Comprobante</h3>
            @php $ext = pathinfo($pago->comprobante_url, PATHINFO_EXTENSION); @endphp
            @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                <img src="{{ Storage::url($pago->comprobante_url) }}"
                     alt="Comprobante"
                     class="w-full max-h-48 object-contain rounded-xl border border-gray-100">
            @else
                <a href="{{ Storage::url($pago->comprobante_url) }}" target="_blank"
                   class="flex items-center gap-2 text-sm text-accent hover:underline font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
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
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Registrado por
                <span class="font-semibold text-gray-700">{{ $pago->user?->name ?? 'Sistema' }}</span>
                el {{ $pago->created_at->format('d/m/Y H:i') }}
            </span>
        </div>
    </div>

</div>

@endsection
