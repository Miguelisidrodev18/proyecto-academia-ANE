@extends('layouts.dashboard')
@section('title', $alumno->nombreCompleto())

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Breadcrumb + acciones --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('alumnos.index') }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('alumnos.index') }}" class="hover:text-accent transition-colors">Alumnos</a>
                <span class="mx-1">/</span>
                <span class="text-gray-600">Perfil</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">{{ $alumno->nombreCompleto() }}</h1>
        </div>
        <div class="flex gap-2">
            <form method="POST" action="{{ route('alumnos.cambiarEstado', $alumno) }}">
                @csrf @method('PATCH')
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-semibold text-sm transition-all border
                               {{ $alumno->estado
                                   ? 'border-gray-200 text-gray-600 hover:bg-red-50 hover:text-red-600 hover:border-red-200'
                                   : 'border-gray-200 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200' }}">
                    @if($alumno->estado)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Desactivar
                    @else
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Activar
                    @endif
                </button>
            </form>
            <a href="{{ route('alumnos.edit', $alumno) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-semibold text-sm
                      bg-primary-dark text-white hover:bg-accent transition-all shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
        </div>
    </div>

    @include('alumnos._flash')

    {{-- Hero card --}}
    <div class="relative bg-gradient-to-br from-primary-dark via-[#0e3d7a] to-[#30A9D9] rounded-3xl p-6 mb-5 text-white overflow-hidden">
        {{-- Decorative circles --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

        <div class="relative flex items-center gap-5 flex-wrap">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center font-black text-3xl flex-shrink-0 shadow-lg
                        {{ $alumno->esIntermedio() ? 'bg-violet-400/30 text-violet-100' : 'bg-white/20 text-white' }}">
                {{ $alumno->inicial() }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 flex-wrap mb-1">
                    <h2 class="text-2xl font-black">{{ $alumno->nombreCompleto() }}</h2>
                    @include('alumnos._badge', ['tipo' => $alumno->tipo, 'size' => 'lg'])
                </div>
                <p class="text-white/60 text-sm font-mono">DNI: {{ $alumno->dni }}</p>
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                    @if($alumno->estado)
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full bg-emerald-400/20 text-emerald-200 border border-emerald-400/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Activo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full bg-white/10 text-white/50 border border-white/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-white/30"></span> Inactivo
                        </span>
                    @endif
                    <span class="text-white/40 text-xs">
                        Registrado {{ $alumno->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            @if($alumno->racha_actual > 0)
            <div class="text-center bg-white/10 backdrop-blur rounded-2xl px-5 py-3 border border-white/20">
                <p class="text-3xl font-black">{{ $alumno->racha_actual }}</p>
                <p class="text-white/60 text-xs mt-0.5">días de racha</p>
                <p class="text-yellow-300 text-sm">🔥</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Info cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-accent/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
                Datos de contacto
            </h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
                    <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-400">Email</p>
                        <p class="text-sm font-semibold text-gray-700 truncate">{{ $alumno->user->email ?? '—' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
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
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-primary-dark/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                    </svg>
                </span>
                Información académica
            </h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
                    <div class="w-8 h-8 rounded-lg bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">DNI</p>
                        <p class="text-sm font-mono font-bold text-gray-700 tracking-widest">{{ $alumno->dni }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
                    <div class="w-8 h-8 rounded-lg bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Racha actual</p>
                        <p class="text-sm font-bold text-gray-700">
                            {{ $alumno->racha_actual }}
                            <span class="font-normal text-gray-400">día(s)</span>
                            @if($alumno->racha_actual > 0) 🔥 @endif
                        </p>
                    </div>
                </div>
                @if($alumno->origen_registro)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
                    <div class="w-8 h-8 rounded-lg bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Origen de registro</p>
                        <p class="text-sm font-semibold text-gray-700 capitalize">{{ $alumno->origen_registro }}</p>
                    </div>
                </div>
                @endif
                @if($alumno->ultimo_acceso)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
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
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-emerald-100 flex items-center justify-center">
                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                    </svg>
                </span>
                Matrículas
            </h3>
            <span class="text-xs bg-gray-100 text-gray-600 font-semibold px-2 py-0.5 rounded-full">{{ $alumno->matriculas->count() }}</span>
        </div>
        <div class="space-y-2">
            @foreach($alumno->matriculas as $matricula)
            <a href="{{ route('matriculas.show', $matricula) }}"
               class="flex items-center justify-between p-3.5 rounded-xl border transition-all duration-150
                      {{ $matricula->estado === 'activa' ? 'border-emerald-200 bg-emerald-50 hover:bg-emerald-100' : 'border-gray-100 bg-gray-50 hover:bg-gray-100' }}">
                <div>
                    <p class="text-sm font-semibold text-gray-700">{{ $matricula->plan->nombre ?? 'Plan sin nombre' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Desde {{ $matricula->fecha_inicio?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                 {{ $matricula->estado === 'activa' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-500' }}">
                        {{ ucfirst($matricula->estado) }}
                    </span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
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
            <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-gradient-to-r from-primary-dark/10 to-primary-light/10 text-primary-dark border border-primary-dark/10">
                {{ $curso->nombre }}
            </span>
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection
