@extends('layouts.dashboard')
@section('title', 'Mi Panel')

@section('content')

{{-- Bienvenida --}}
<div class="mb-6">
    <h1 class="text-2xl font-black text-primary-dark">
        ¡Bienvenido, {{ explode(' ', auth()->user()->name)[0] }}!
    </h1>
    <p class="text-gray-500 text-sm mt-1">
        Panel del alumno — Academia Nueva Era
    </p>
</div>

{{-- Estado de matrícula --}}
@if(!$alumno)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-amber-800 font-bold">Perfil de alumno no configurado</p>
            <p class="text-amber-600 text-sm mt-0.5">Contacta al administrador para completar tu registro.</p>
        </div>
    </div>
@elseif(!$matricula)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-amber-800 font-bold">Sin matrícula activa</p>
            <p class="text-amber-600 text-sm mt-0.5">Comunícate con la academia para matricularte en un plan.</p>
        </div>
    </div>
@else
    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        {{-- Plan --}}
        <div class="bg-gradient-to-br from-primary-dark to-primary-light rounded-2xl p-5 shadow-sm col-span-2 lg:col-span-1">
            <p class="text-white/60 text-xs font-semibold uppercase tracking-wide mb-1">Plan activo</p>
            <p class="text-white font-black text-xl leading-tight">{{ $matricula->plan->nombre }}</p>
            @if($matricula->plan->acceso_ilimitado)
                <p class="text-white/70 text-xs mt-1">Acceso ilimitado</p>
            @else
                <p class="text-white/70 text-xs mt-1">Vence en {{ $matricula->diasRestantes() }} días</p>
            @endif
        </div>
        {{-- Cursos --}}
        <a href="{{ route('alumno.mis-cursos') }}"
           class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 block group">
            <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            <p class="text-3xl font-black text-gray-800">{{ $cursos->count() }}</p>
            <p class="text-xs text-gray-400 font-medium mt-0.5">Cursos disponibles</p>
        </a>
        {{-- Estado --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Matrícula activa
            </span>
            <p class="text-xs text-gray-400 font-medium mt-2">Acceso habilitado</p>
        </div>
    </div>
@endif

{{-- Cursos (preview) --}}
<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-black text-primary-dark">Mis Cursos</h2>
    @if($cursos->isNotEmpty())
        <a href="{{ route('alumno.mis-cursos') }}"
           class="text-xs text-accent font-semibold hover:underline">Ver todos →</a>
    @endif
</div>

@if($cursos->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-16 text-center">
        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-semibold">Contenido en construcción</p>
        <p class="text-gray-400 text-sm mt-1">Pronto encontrarás tus cursos aquí.</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($cursos->take(6) as $curso)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                        hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group">
                <div class="bg-gradient-to-r
                    {{ $curso->nivel === 'pollito' ? 'from-blue-600 to-blue-400' : 'from-primary-dark to-primary-light' }}
                    px-4 py-4">
                    <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest">{{ $curso->nivelLabel() }}</p>
                    <h3 class="text-base font-black text-white mt-0.5 leading-tight">{{ $curso->nombre }}</h3>
                </div>
                <div class="px-4 py-3 flex items-center justify-between">
                    <span class="text-xs text-gray-500">{{ $curso->tipoLabel() }}</span>
                    <a href="{{ route('alumno.mis-cursos') }}"
                       class="text-xs font-bold text-accent hover:text-secondary transition-colors">
                        Ver →
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    @if($cursos->count() > 6)
        <div class="mt-4 text-center">
            <a href="{{ route('alumno.mis-cursos') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
                      bg-gradient-to-r from-primary-dark to-primary-light
                      hover:from-accent hover:to-secondary transition-all duration-300 shadow-md">
                Ver todos los cursos ({{ $cursos->count() }})
            </a>
        </div>
    @endif
@endif

@endsection
