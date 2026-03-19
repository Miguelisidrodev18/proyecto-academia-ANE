@extends('layouts.dashboard')
@section('title', 'Panel Docente')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-black text-primary-dark">
        ¡Bienvenido, {{ explode(' ', auth()->user()->name)[0] }}!
    </h1>
    <p class="text-gray-500 text-sm mt-1">
        Panel del docente — Academia Nueva Era
    </p>
</div>

{{-- Módulos disponibles para docente --}}
<h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Módulos disponibles</h2>
<div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
    <a href="{{ route('cursos.index') }}"
       class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100
              hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group text-center">
        <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center mx-auto mb-3
                    group-hover:scale-110 transition-transform duration-200">
            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <p class="text-xs font-bold text-gray-600 leading-tight">Cursos</p>
        <p class="text-[10px] text-green-500 mt-1 font-bold">Activo</p>
    </a>

    <a href="{{ route('dashboard.asistencia') }}"
       class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100
              hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group text-center">
        <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center mx-auto mb-3
                    group-hover:scale-110 transition-transform duration-200">
            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <p class="text-xs font-bold text-gray-600 leading-tight">Asistencia</p>
        <p class="text-[10px] text-gray-400 mt-1 font-medium">Próximamente</p>
    </a>

    <a href="{{ route('dashboard.aula-virtual') }}"
       class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100
              hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group text-center">
        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center mx-auto mb-3
                    group-hover:scale-110 transition-transform duration-200">
            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-xs font-bold text-gray-600 leading-tight">Aula Virtual</p>
        <p class="text-[10px] text-gray-400 mt-1 font-medium">Próximamente</p>
    </a>
</div>

{{-- Info --}}
<div class="mt-6 bg-gradient-to-r from-primary-dark to-primary-light rounded-2xl p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <div>
        <p class="text-white font-bold text-sm">Módulo activo: Cursos</p>
        <p class="text-white/60 text-xs mt-0.5">
            Gestiona los cursos, agrega clases y materiales desde el módulo de Cursos.
        </p>
    </div>
</div>

@endsection
