@extends('layouts.dashboard')
@section('title', 'Mis Cursos')

@section('content')

{{-- Bienvenida + estado matrícula --}}
<div class="mb-6">
    <h1 class="text-2xl font-black text-primary-dark">
        ¡Hola, {{ explode(' ', auth()->user()->name)[0] }}!
    </h1>
    <p class="text-gray-500 text-sm mt-1">Aquí encontrarás todos los cursos de tu plan activo.</p>
</div>

{{-- Banner de matrícula --}}
@if(!$alumno)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-amber-800 font-bold text-sm">Tu perfil de alumno no está configurado</p>
            <p class="text-amber-600 text-xs mt-0.5">Contacta al administrador para completar tu registro.</p>
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
            <p class="text-amber-800 font-bold text-sm">No tienes una matrícula activa</p>
            <p class="text-amber-600 text-xs mt-0.5">Comunícate con la academia para matricularte en un plan.</p>
        </div>
    </div>
@else
    {{-- Info de matrícula --}}
    <div class="bg-gradient-to-r from-primary-dark to-primary-light rounded-2xl p-5 mb-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-white font-bold text-sm">Plan: {{ $matricula->plan->nombre }}</p>
            <p class="text-white/70 text-xs mt-0.5">
                Matrícula activa ·
                @if($matricula->plan->acceso_ilimitado)
                    Acceso ilimitado
                @else
                    Vence el {{ \Carbon\Carbon::parse($matricula->fecha_fin)->format('d/m/Y') }}
                    ({{ $matricula->diasRestantes() }} días restantes)
                @endif
            </p>
        </div>
        <div class="text-right flex-shrink-0">
            <p class="text-white font-black text-xl">{{ $cursos->count() }}</p>
            <p class="text-white/60 text-xs">cursos</p>
        </div>
    </div>
@endif

{{-- Cursos --}}
<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-black text-primary-dark">Mis Cursos</h2>
    @if($cursos->isNotEmpty())
        <span class="text-xs text-gray-400 font-medium">{{ $cursos->count() }} {{ $cursos->count() === 1 ? 'curso' : 'cursos' }} disponibles</span>
    @endif
</div>

@if($cursos->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-20 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-5 shadow-inner">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <p class="text-gray-600 font-bold text-base">Contenido en construcción</p>
        <p class="text-gray-400 text-sm mt-2 max-w-xs mx-auto">
            Los cursos de tu plan estarán disponibles muy pronto. ¡Estamos preparando el mejor contenido para ti!
        </p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($cursos as $curso)
            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                        hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                {{-- Header con gradiente según nivel --}}
                <div class="bg-gradient-to-br
                    {{ $curso->nivel === 'pollito' ? 'from-blue-600 to-blue-400' : ($curso->nivel === 'intermedio' ? 'from-primary-dark to-primary-light' : 'from-violet-700 to-violet-500') }}
                    px-5 pt-5 pb-8 relative overflow-hidden">

                    {{-- Decoración de fondo --}}
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-white/5"></div>
                    <div class="absolute -right-2 -bottom-4 w-16 h-16 rounded-full bg-white/5"></div>

                    {{-- Badges --}}
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold
                                     bg-white/20 text-white uppercase tracking-wide">
                            {{ $curso->nivelLabel() }}
                        </span>
                        @if($curso->grado)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold
                                         bg-amber-400/25 text-amber-200">
                                {{ $curso->gradoLabel() }}
                            </span>
                        @endif
                    </div>

                    {{-- Nombre --}}
                    <h3 class="text-xl font-black text-white leading-tight relative z-10">{{ $curso->nombre }}</h3>

                    {{-- Tipo --}}
                    <p class="text-white/60 text-xs mt-1 relative z-10">{{ $curso->tipoLabel() }}</p>
                </div>

                {{-- Cuerpo --}}
                <div class="px-5 py-4 -mt-3 relative">
                    {{-- Descripción --}}
                    @if($curso->descripcion)
                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-4">{{ $curso->descripcion }}</p>
                    @else
                        <p class="text-gray-300 text-sm italic mb-4">Contenido en preparación.</p>
                    @endif

                    {{-- Próxima clase --}}
                    @php $proximaClase = $curso->proximaClase(); @endphp
                    @if($proximaClase)
                        <div class="flex items-center gap-2 mb-4 p-2.5 rounded-xl bg-accent/5 border border-accent/10">
                            <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-accent leading-none truncate">{{ $proximaClase->titulo }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($proximaClase->fecha)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Botón Entrar --}}
                    @if($matricula && $matricula->tieneAcceso())
                        @if($proximaClase && $proximaClase->zoom_link)
                            <a href="{{ $proximaClase->zoom_link }}" target="_blank"
                               class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-white
                                      bg-gradient-to-r from-primary-dark to-primary-light
                                      hover:from-accent hover:to-secondary transition-all duration-300
                                      shadow-md hover:shadow-lg hover:-translate-y-0.5 group-hover:scale-[1.02]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Entrar a la clase
                            </a>
                        @else
                            <button disabled
                                    class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm
                                           bg-gray-100 text-gray-400 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Sin clase programada
                            </button>
                        @endif
                    @else
                        <button disabled
                                class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm
                                       bg-red-50 text-red-400 cursor-not-allowed border border-red-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Acceso suspendido
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
