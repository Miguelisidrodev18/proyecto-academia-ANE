@extends('layouts.dashboard')
@section('title', $curso->nombre)

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('cursos.index') }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('cursos.index') }}" class="hover:text-accent transition-colors">Cursos</a>
                <span class="mx-1">/</span><span class="text-gray-600">{{ $curso->nombre }}</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">{{ $curso->nombre }}</h1>
        </div>
        @if(auth()->user()->isAdmin())
        <div class="flex items-center gap-2">
            <a href="{{ route('cursos.edit', $curso) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-bold text-xs text-white
                      bg-gradient-to-r from-primary-dark to-primary-light hover:from-accent hover:to-secondary
                      transition-all duration-300 shadow-sm hover:shadow-md">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
        </div>
        @endif
    </div>

    @include('cursos._flash')

    {{-- Info card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-5">
        <div class="bg-gradient-to-r
            {{ $curso->nivel === 'pollito' ? 'from-blue-600 to-blue-400' : ($curso->nivel === 'intermedio' ? 'from-primary-dark to-primary-light' : 'from-violet-700 to-violet-500') }}
            px-6 py-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white/60 text-xs font-semibold uppercase tracking-widest mb-1">{{ $curso->nivelLabel() }}</p>
                    <h2 class="text-2xl font-black text-white">{{ $curso->nombre }}</h2>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-white/80 text-sm">{{ $curso->tipoLabel() }}</span>
                        @if($curso->grado)
                            <span class="text-white/80 text-sm">· {{ $curso->gradoLabel() }}</span>
                        @endif
                    </div>
                </div>
                <span @class([
                    'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold',
                    'bg-emerald-400/20 text-emerald-200 border border-emerald-400/30' => $curso->activo,
                    'bg-white/10 text-white/50 border border-white/20'                => !$curso->activo,
                ])>
                    <span class="w-1.5 h-1.5 rounded-full {{ $curso->activo ? 'bg-emerald-400' : 'bg-white/40' }}"></span>
                    {{ $curso->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
        </div>

        <div class="p-6">
            @if($curso->descripcion)
                <p class="text-gray-600 leading-relaxed">{{ $curso->descripcion }}</p>
            @else
                <p class="text-gray-400 italic text-sm">Sin descripción registrada.</p>
            @endif

            {{-- Planes asociados --}}
            @if($curso->planes->isNotEmpty())
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Incluido en los planes:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($curso->planes as $plan)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                         bg-primary-dark/5 text-primary-dark border border-primary-dark/10">
                                {{ $plan->nombre }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Alumnos inscritos vía plan activo --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-5">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-700">Alumnos inscritos</h3>
            <span class="text-xs text-gray-400">{{ $alumnos->count() }} {{ $alumnos->count() === 1 ? 'alumno' : 'alumnos' }}</span>
        </div>
        @if($alumnos->isEmpty())
            <div class="py-10 text-center">
                <p class="text-gray-400 text-sm">No hay alumnos inscritos en este curso.</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($alumnos as $alumno)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-dark to-primary-light
                                    flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">
                            {{ strtoupper(substr($alumno->user->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{ $alumno->user->name ?? '—' }}</p>
                            <p class="text-xs text-gray-400">{{ $alumno->user->email ?? '' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold
                                     bg-emerald-50 text-emerald-600 border border-emerald-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Plan activo
                        </span>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('alumnos.show', $alumno) }}"
                               class="text-xs text-accent hover:underline font-medium">Ver perfil</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Clases --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-700">Clases recientes</h3>
            <span class="text-xs text-gray-400">{{ $curso->clases->count() }} clases</span>
        </div>
        @if($curso->clases->isEmpty())
            <div class="py-12 text-center">
                <p class="text-gray-400 text-sm">No hay clases registradas aún.</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($curso->clases->take(5) as $clase)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{ $clase->titulo }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($clase->fecha)->format('d/m/Y H:i') }}</p>
                        </div>
                        @if($clase->grabada && $clase->grabacion_url)
                            <a href="{{ $clase->grabacion_url }}" target="_blank"
                               class="text-xs text-accent font-semibold hover:underline">Ver grabación</a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection
