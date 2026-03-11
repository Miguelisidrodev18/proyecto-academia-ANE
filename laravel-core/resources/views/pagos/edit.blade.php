@extends('layouts.dashboard')
@section('title', 'Editar Pago #' . $pago->id)

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-gray-400 mb-5">
        <a href="{{ route('pagos.index') }}" class="hover:text-primary-dark transition-colors font-medium">Pagos</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('pagos.show', $pago) }}" class="hover:text-primary-dark transition-colors font-medium">
            Pago #{{ $pago->id }}
        </a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600 font-semibold">Editar</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('pagos.show', $pago) }}"
           class="w-10 h-10 rounded-xl border border-gray-200 flex items-center justify-center text-gray-400
                  hover:text-primary-dark hover:border-gray-300 hover:-translate-y-0.5 transition-all flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-2xl font-black text-primary-dark leading-tight">Editar Pago #{{ $pago->id }}</h1>
                @include('pagos._badge', ['estado' => $pago->estado])
            </div>
            <p class="text-gray-400 text-sm mt-0.5 truncate">
                {{ $pago->matricula->alumno->nombreCompleto() }} &mdash; {{ $pago->matricula->plan->nombre }}
            </p>
        </div>
        <div class="hidden sm:block text-right flex-shrink-0">
            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold">Saldo disponible</p>
            <p class="text-xl font-black text-primary-dark">S/. {{ number_format($saldoDisponible, 2) }}</p>
        </div>
    </div>

    @include('pagos._flash')

    <form method="POST" action="{{ route('pagos.update', $pago) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        @include('pagos._form')

        {{-- Botones --}}
        <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
            <button type="submit"
                    class="flex items-center gap-2 px-7 py-3 rounded-xl font-bold text-sm text-white
                           bg-gradient-to-r from-primary-dark to-primary-light
                           hover:from-accent hover:to-secondary hover:-translate-y-0.5
                           transition-all duration-300 shadow-md hover:shadow-lg active:translate-y-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M5 13l4 4L19 7"/>
                </svg>
                Guardar cambios
            </button>
            <a href="{{ route('pagos.show', $pago) }}"
               class="px-6 py-3 rounded-xl font-semibold text-sm text-gray-600
                      border border-gray-200 hover:bg-gray-50 hover:-translate-y-0.5 transition-all">
                Cancelar
            </a>
            <a href="{{ route('pagos.show', $pago) }}"
               class="ml-auto px-4 py-3 rounded-xl font-semibold text-sm text-red-500
                      border border-red-100 hover:bg-red-50 hover:-translate-y-0.5 transition-all">
                Descartar cambios
            </a>
        </div>
    </form>

</div>

@endsection