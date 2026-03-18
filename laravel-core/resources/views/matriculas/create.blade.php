@extends('layouts.dashboard')
@section('title', 'Nueva Matrícula')
@section('content')
<div class="max-w-3xl mx-auto">
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
                <span class="mx-1">/</span><span class="text-gray-600">Nueva</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">Nueva Matrícula</h1>
        </div>
        <span class="text-xs text-primary-dark/70 font-medium flex items-center gap-1 bg-primary-dark/5 border border-primary-dark/10 px-3 py-1.5 rounded-full">
            <svg class="w-3 h-3 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            El precio se calcula al elegir el plan
        </span>
    </div>
    @include('matriculas._flash')
    <form method="POST" action="{{ route('matriculas.store') }}"
          x-data="{ bloqueado: false }"
          @bloquear-submit.window="bloqueado = $event.detail.bloqueado">
        @csrf
        @include('matriculas._form')
        <div class="flex items-center gap-3 mt-5">
            <button type="submit"
                    :disabled="bloqueado"
                    :class="bloqueado
                        ? 'opacity-50 cursor-not-allowed from-gray-400 to-gray-500'
                        : 'hover:from-accent hover:to-secondary hover:shadow-lg hover:-translate-y-0.5'"
                    class="inline-flex items-center gap-2 px-7 py-3 rounded-xl font-bold text-sm text-white
                           bg-gradient-to-r from-primary-dark to-primary-light
                           transition-all duration-300 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <span x-text="bloqueado ? 'Confirma la advertencia para continuar' : 'Registrar matrícula'"></span>
            </button>
            <a href="{{ route('matriculas.index') }}"
               class="px-6 py-3 rounded-xl font-semibold text-sm text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection