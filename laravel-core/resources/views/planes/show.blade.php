@extends('layouts.dashboard')
@section('title', $plan->nombre)

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('planes.index') }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('planes.index') }}" class="hover:text-accent transition-colors">Planes</a>
                <span class="mx-1">/</span><span class="text-gray-600">{{ $plan->nombre }}</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">{{ $plan->nombre }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('planes.toggle', $plan) }}">
                @csrf @method('PATCH')
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold border transition-all
                               {{ $plan->activo
                                    ? 'border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100'
                                    : 'border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100' }}">
                    @if($plan->activo)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Desactivar
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Activar
                    @endif
                </button>
            </form>
            <a href="{{ route('planes.edit', $plan) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white
                      bg-gradient-to-r from-primary-dark to-primary-light
                      hover:from-accent hover:to-secondary transition-all duration-300 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
        </div>
    </div>

    @include('planes._flash')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Card principal --}}
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-b from-primary-dark to-primary-light rounded-2xl shadow-lg overflow-hidden">
                {{-- Header card --}}
                <div class="px-6 pt-6 pb-4 relative">
                    @if($plan->acceso_ilimitado)
                        <span class="absolute top-4 right-4 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-400/20 text-amber-300 text-xs font-bold border border-amber-400/30">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Ilimitado
                        </span>
                    @endif
                    <p class="text-white/60 text-xs font-semibold uppercase tracking-widest mb-1">Plan</p>
                    <h2 class="text-2xl font-black text-white">{{ $plan->nombre }}</h2>
                </div>

                {{-- Precio --}}
                <div class="px-6 py-4 bg-white/10 border-y border-white/10">
                    <div class="flex items-end gap-1">
                        <span class="text-4xl font-black text-white">S/. {{ number_format($plan->precio, 0) }}</span>
                        <span class="text-white/50 text-sm mb-1">
                            /
                            @if($plan->acceso_ilimitado)
                                ilimitado
                            @else
                                {{ $plan->duracion_meses }} {{ $plan->duracion_meses == 1 ? 'mes' : 'meses' }}
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Detalles --}}
                <div class="px-6 py-5 space-y-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white/50 text-xs">Duración</p>
                            <p class="text-white text-sm font-semibold">
                                @if($plan->acceso_ilimitado)
                                    Acceso ilimitado
                                @else
                                    {{ $plan->duracion_meses }} {{ $plan->duracion_meses == 1 ? 'mes' : 'meses' }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white/50 text-xs">Alumnos activos</p>
                            <p class="text-white text-sm font-semibold">{{ $matriculasActivas->count() }} inscritos</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                            <div class="w-2 h-2 rounded-full {{ $plan->activo ? 'bg-emerald-400' : 'bg-gray-400' }}"></div>
                        </div>
                        <div>
                            <p class="text-white/50 text-xs">Estado</p>
                            <p class="text-sm font-semibold {{ $plan->activo ? 'text-emerald-300' : 'text-gray-300' }}">
                                {{ $plan->activo ? 'Activo' : 'Inactivo' }}
                            </p>
                        </div>
                    </div>

                    @if($plan->descripcion)
                        <div class="pt-2 border-t border-white/10">
                            <p class="text-white/50 text-xs mb-1">Descripción</p>
                            <p class="text-white/80 text-xs leading-relaxed">{{ $plan->descripcion }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Acciones --}}
            <div class="mt-4 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-sm font-bold text-gray-700">Acciones</h3>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('planes.edit', $plan) }}"
                       class="flex items-center gap-2.5 w-full px-4 py-2.5 rounded-xl text-sm font-medium
                              text-gray-700 hover:bg-primary-dark/5 hover:text-primary-dark transition-all">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar plan
                    </a>
                    <form method="POST" action="{{ route('planes.toggle', $plan) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="flex items-center gap-2.5 w-full px-4 py-2.5 rounded-xl text-sm font-medium transition-all
                                       {{ $plan->activo ? 'text-amber-700 hover:bg-amber-50' : 'text-emerald-700 hover:bg-emerald-50' }}">
                            <svg class="w-4 h-4 {{ $plan->activo ? 'text-amber-400' : 'text-emerald-400' }}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($plan->activo)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @endif
                            </svg>
                            {{ $plan->activo ? 'Desactivar plan' : 'Activar plan' }}
                        </button>
                    </form>
                    @if($matriculasActivas->isEmpty())
                        <form method="POST" action="{{ route('planes.destroy', $plan) }}"
                              onsubmit="return confirm('¿Eliminar el plan {{ $plan->nombre }}? Esta acción no se puede deshacer.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="flex items-center gap-2.5 w-full px-4 py-2.5 rounded-xl text-sm font-medium
                                           text-red-600 hover:bg-red-50 transition-all">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar plan
                            </button>
                        </form>
                    @else
                        <p class="px-4 py-2 text-xs text-gray-400 italic">No se puede eliminar: tiene {{ $matriculasActivas->count() }} matrícula(s) activa(s).</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Cursos del plan --}}
        <div class="lg:col-span-2 mb-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-accent/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700">Cursos incluidos</h3>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-accent/10 text-accent">
                        {{ $plan->cursos->count() }}
                    </span>
                </div>
                @if($plan->cursos->isEmpty())
                    <div class="py-10 text-center">
                        <p class="text-gray-400 text-sm">No hay cursos asignados a este plan.</p>
                        <a href="{{ route('planes.edit', $plan) }}" class="text-xs text-accent font-semibold hover:underline mt-1 inline-block">
                            Agregar cursos →
                        </a>
                    </div>
                @else
                    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($plan->cursos as $curso)
                            <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-gray-50/50">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ $curso->nivel === 'pollito' ? 'bg-blue-100' : 'bg-primary-dark/10' }}">
                                    <svg class="w-4 h-4 {{ $curso->nivel === 'pollito' ? 'text-blue-600' : 'text-primary-dark' }}"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-700 truncate">{{ $curso->nombre }}</p>
                                    <p class="text-xs text-gray-400">{{ $curso->nivelLabel() }}{{ $curso->grado ? ' · ' . $curso->gradoLabel() : '' }}</p>
                                </div>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full font-bold flex-shrink-0
                                             {{ $curso->tipo === 'preuniversitario' ? 'bg-violet-100 text-violet-600' : 'bg-blue-100 text-blue-600' }}">
                                    {{ $curso->tipoLabel() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Matrículas activas --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-primary-dark/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700">Alumnos inscritos actualmente</h3>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-primary-dark/10 text-primary-dark">
                        {{ $matriculasActivas->count() }}
                    </span>
                </div>

                @if($matriculasActivas->isEmpty())
                    <div class="py-16 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-inner">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-semibold text-sm">Sin alumnos activos en este plan</p>
                        <p class="text-gray-400 text-xs mt-1">
                            <a href="{{ route('matriculas.create') }}" class="text-accent hover:underline">Registrar una matrícula</a>
                        </p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($matriculasActivas as $matricula)
                            <div class="px-5 py-3.5 flex items-center justify-between gap-3 hover:bg-gray-50/50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light
                                                flex items-center justify-center text-xs font-black text-white flex-shrink-0 shadow-sm">
                                        {{ $matricula->alumno->inicial() }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 group-hover:text-primary-dark transition-colors">
                                            {{ $matricula->alumno->nombreCompleto() }}
                                        </p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $matricula->alumno->dni }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 text-right">
                                    <div>
                                        <p class="text-xs text-gray-400">Vence</p>
                                        <p class="text-xs font-semibold text-gray-600">{{ $matricula->fecha_fin?->format('d/m/Y') }}</p>
                                    </div>
                                    <span class="text-xs font-bold px-2 py-1 rounded-full
                                                 {{ $matricula->diasRestantes() <= 7 ? 'bg-red-50 text-red-600' : ($matricula->diasRestantes() <= 30 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600') }}">
                                        {{ max(0, $matricula->diasRestantes()) }}d
                                    </span>
                                    <a href="{{ route('matriculas.show', $matricula) }}"
                                       class="p-1.5 rounded-lg text-gray-300 hover:text-accent hover:bg-accent/10 transition-all opacity-0 group-hover:opacity-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
