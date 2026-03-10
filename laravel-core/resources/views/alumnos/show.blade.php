@extends('layouts.dashboard')
@section('title', $alumno->nombreCompleto())

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('alumnos.index') }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-gray-300 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-black text-primary-dark">Perfil del Alumno</h1>
        <div class="ml-auto flex gap-2">
            <form method="POST" action="{{ route('alumnos.cambiarEstado', $alumno) }}">
                @csrf @method('PATCH')
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm transition-all
                               {{ $alumno->estado
                                   ? 'border border-gray-200 text-gray-600 hover:bg-red-50 hover:text-red-600 hover:border-red-200'
                                   : 'border border-gray-200 text-gray-600 hover:bg-green-50 hover:text-green-600 hover:border-green-200' }}">
                    {{ $alumno->estado ? 'Desactivar' : 'Activar' }}
                </button>
            </form>
            <a href="{{ route('alumnos.edit', $alumno) }}"
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

    @include('alumnos._flash')

    {{-- Tarjeta de perfil --}}
    <div class="bg-gradient-to-br from-primary-dark to-[#30A9D9] rounded-2xl p-6 mb-5 text-white">
        <div class="flex items-center gap-5 flex-wrap">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center font-black text-3xl flex-shrink-0
                        {{ $alumno->esIntermedio() ? 'bg-purple-400/30 text-purple-200' : 'bg-white/20 text-white' }}">
                {{ $alumno->inicial() }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 flex-wrap mb-1">
                    <h2 class="text-2xl font-black">{{ $alumno->nombreCompleto() }}</h2>
                    @include('alumnos._badge', ['tipo' => $alumno->tipo, 'size' => 'lg'])
                </div>
                <p class="text-white/70 text-sm font-mono">DNI: {{ $alumno->dni }}</p>
                <div class="flex items-center gap-2 mt-2">
                    @if($alumno->estado)
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full
                                     bg-green-400/20 text-green-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span> Activo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full
                                     bg-white/10 text-white/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-white/30"></span> Inactivo
                        </span>
                    @endif
                    <span class="text-white/40 text-xs">
                        Registrado {{ $alumno->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Datos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Datos de contacto</h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Email</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $alumno->user->email ?? '—' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Teléfono</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $alumno->telefono ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Información académica</h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">DNI</p>
                        <p class="text-sm font-mono font-bold text-gray-700">{{ $alumno->dni }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Racha actual</p>
                        <p class="text-sm font-bold text-gray-700">
                            {{ $alumno->racha_actual }}
                            <span class="font-normal text-gray-400">día(s)</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Origen de registro</p>
                        <p class="text-sm font-semibold text-gray-700 capitalize">{{ $alumno->origen_registro ?? '—' }}</p>
                    </div>
                </div>
                @if($alumno->ultimo_acceso)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Último acceso</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $alumno->ultimo_acceso->format('d/m/Y') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Matrículas --}}
    @if($alumno->matriculas->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Matrículas</h3>
        <div class="space-y-2">
            @foreach($alumno->matriculas as $matricula)
            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                <div>
                    <p class="text-sm font-semibold text-gray-700">{{ $matricula->plan->nombre ?? 'Plan sin nombre' }}</p>
                    <p class="text-xs text-gray-400">Desde {{ $matricula->fecha_inicio?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full
                             {{ $matricula->estado === 'activa' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ ucfirst($matricula->estado) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Cursos inscritos --}}
    @if($alumno->cursos->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Cursos inscritos</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($alumno->cursos as $curso)
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-primary-dark/10 text-primary-dark">
                {{ $curso->nombre }}
            </span>
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection
