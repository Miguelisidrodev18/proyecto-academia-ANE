@extends('layouts.dashboard')
@section('title', 'Cursos')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total cursos</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['activos'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Cursos activos</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['pollito'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Nivel Pollito</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['intermedio'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Nivel Intermedio</p>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex items-center justify-between mb-5 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Cursos</h1>
        <p class="text-gray-400 text-sm mt-0.5">
            {{ $cursos->count() }} {{ $cursos->count() === 1 ? 'curso registrado' : 'cursos registrados' }}
        </p>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('cursos.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
              bg-gradient-to-r from-primary-dark to-primary-light
              hover:from-accent hover:to-secondary transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Curso
    </a>
    @endif
</div>

@include('cursos._flash')

@if($cursos->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-24 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-semibold text-base">No hay cursos registrados</p>
        @if(auth()->user()->isAdmin())
        <p class="text-gray-400 text-sm mt-1">
            <a href="{{ route('cursos.create') }}" class="text-accent font-semibold hover:underline">Crear el primer curso</a>
        </p>
        @endif
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($cursos as $curso)
            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                        hover:shadow-lg hover:-translate-y-1 transition-all duration-300
                        {{ !$curso->activo ? 'opacity-60' : '' }}">

                {{-- Header de la card --}}
                <div class="bg-gradient-to-r
                    {{ $curso->nivel === 'pollito' ? 'from-blue-600 to-blue-400' : ($curso->nivel === 'intermedio' ? 'from-primary-dark to-primary-light' : 'from-violet-700 to-violet-500') }}
                    px-5 py-5 relative">

                    {{-- Badges --}}
                    <div class="absolute top-3 right-3 flex flex-col gap-1 items-end">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                                     bg-white/15 text-white text-[10px] font-bold uppercase tracking-wide">
                            {{ $curso->tipoLabel() }}
                        </span>
                        @if($curso->grado)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                                         bg-amber-400/20 text-amber-200 text-[10px] font-bold border border-amber-400/30">
                                {{ $curso->gradoLabel() }}
                            </span>
                        @endif
                    </div>

                    <p class="text-white/60 text-xs font-semibold uppercase tracking-widest mb-1">
                        {{ $curso->nivelLabel() }}
                    </p>
                    <h2 class="text-xl font-black text-white leading-tight pr-16">{{ $curso->nombre }}</h2>

                    {{-- Métricas --}}
                    <div class="flex items-center gap-4 mt-3">
                        <span class="text-white/70 text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $curso->alumnos_count }} alumnos
                        </span>
                        <span class="text-white/70 text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            {{ $curso->clases_count }} clases
                        </span>
                    </div>
                </div>

                {{-- Cuerpo --}}
                <div class="px-5 py-4">
                    @if($curso->descripcion)
                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-2">{{ $curso->descripcion }}</p>
                    @else
                        <p class="text-gray-300 text-sm italic">Sin descripción.</p>
                    @endif

                    <div class="mt-3 flex items-center justify-between">
                        <span @class([
                            'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold',
                            'bg-emerald-50 text-emerald-700 border border-emerald-200' => $curso->activo,
                            'bg-gray-100 text-gray-500 border border-gray-200'         => !$curso->activo,
                        ])>
                            <span class="w-1.5 h-1.5 rounded-full {{ $curso->activo ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                            {{ $curso->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-5 pb-4 pt-0 flex items-center gap-2">
                    <a href="{{ route('cursos.show', $curso) }}"
                       class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-primary-dark/5 text-primary-dark
                              hover:bg-primary-dark hover:text-white transition-all duration-200">
                        Ver detalle
                    </a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('cursos.edit', $curso) }}"
                       class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-gray-100 text-gray-600
                              hover:bg-gray-200 transition-all duration-200">
                        Editar
                    </a>
                    <form method="POST" action="{{ route('cursos.toggle', $curso) }}">
                        @csrf @method('PATCH')
                        <button type="submit" title="{{ $curso->activo ? 'Desactivar' : 'Activar' }}"
                                class="p-2 rounded-xl transition-all duration-200
                                       {{ $curso->activo ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}">
                            @if($curso->activo)
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            @else
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
