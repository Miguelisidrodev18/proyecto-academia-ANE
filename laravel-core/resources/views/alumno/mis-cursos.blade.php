@extends('layouts.dashboard')
@section('title', 'Mis Cursos')

@section('content')

@php
    $nombre = explode(' ', auth()->user()->name)[0];
@endphp

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- HERO / BANNER DE PLAN                                       --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if(!$alumno)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-8 flex items-center gap-4">
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
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-8 flex items-center gap-4">
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
    {{-- Banner de plan (VIP o estándar) --}}
    <div class="relative rounded-3xl overflow-hidden mb-8 shadow-xl"
         @if($matricula->plan->esVip())
             style="background: linear-gradient(135deg, #1a0a00 0%, #3d1f00 50%, #6b3500 100%);
                    box-shadow: 0 8px 40px rgba(251,191,36,0.3);"
         @else
             style="background: linear-gradient(135deg, #082B59 0%, #1a5ba0 60%, #30A9D9 100%);"
         @endif>

        {{-- Decoraciones --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            @if($matricula->plan->esVip())
                <div class="absolute inset-0" style="background: radial-gradient(ellipse at 90% 0%, rgba(251,191,36,0.2) 0%, transparent 55%);"></div>
                <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full border border-amber-400/10"></div>
                <div class="absolute -bottom-8 right-24 w-32 h-32 rounded-full border border-amber-400/10"></div>
            @else
                <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/5"></div>
                <div class="absolute top-8 right-32 w-32 h-32 rounded-full bg-white/5"></div>
            @endif
        </div>

        <div class="relative z-10 px-6 py-6 md:px-10 flex flex-col sm:flex-row sm:items-center gap-4">
            {{-- Ícono --}}
            <div class="w-14 h-14 rounded-2xl {{ $matricula->plan->esVip() ? 'bg-amber-400/20' : 'bg-white/10' }} flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 {{ $matricula->plan->esVip() ? 'text-amber-300' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            {{-- Info del plan --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    @if($matricula->plan->esVip())
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-gradient-to-r from-amber-400 to-yellow-300 text-amber-900 shadow">
                            💎 VIP
                        </span>
                    @endif
                    <span class="text-xs font-semibold {{ $matricula->plan->esVip() ? 'text-amber-300/70' : 'text-white/60' }} uppercase tracking-wide">
                        Plan activo
                    </span>
                </div>
                <p class="{{ $matricula->plan->esVip() ? 'text-amber-200' : 'text-white' }} font-black text-xl leading-tight">
                    {{ $matricula->plan->tipoIcono() }} {{ $matricula->plan->nombre }}
                </p>
                <p class="{{ $matricula->plan->esVip() ? 'text-amber-300/60' : 'text-white/60' }} text-xs mt-1">
                    Matrícula activa ·
                    @if($matricula->plan->acceso_ilimitado)
                        Acceso ilimitado
                    @else
                        Vence el {{ \Carbon\Carbon::parse($matricula->fecha_fin)->format('d/m/Y') }}
                        ({{ $matricula->diasRestantes() }} días)
                    @endif
                </p>
            </div>

            {{-- Contador de cursos --}}
            <div class="text-center flex-shrink-0 {{ $matricula->plan->esVip() ? 'text-amber-200' : 'text-white' }}">
                <p class="text-4xl font-black leading-none">{{ $cursos->count() }}</p>
                <p class="{{ $matricula->plan->esVip() ? 'text-amber-300/60' : 'text-white/60' }} text-xs mt-1">
                    {{ $cursos->count() === 1 ? 'curso' : 'cursos' }}
                </p>
            </div>
        </div>
    </div>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- GRID DE CURSOS (Netflix card style)                         --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="flex items-center justify-between mb-5">
    <h2 class="text-xl font-black text-primary-dark flex items-center gap-2">
        <span class="w-1 h-6 rounded-full bg-accent inline-block"></span>
        Mis Cursos
    </h2>
    @if($cursos->isNotEmpty())
        <span class="text-xs text-gray-400 font-medium">
            {{ $cursos->count() }} {{ $cursos->count() === 1 ? 'curso disponible' : 'cursos disponibles' }}
        </span>
    @endif
</div>

@if($cursos->isEmpty())
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm py-24 text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <p class="text-gray-600 font-bold text-lg">Contenido en construcción</p>
        <p class="text-gray-400 text-sm mt-2 max-w-xs mx-auto leading-relaxed">
            Los cursos de tu plan estarán disponibles muy pronto. ¡Estamos preparando el mejor contenido para ti!
        </p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($cursos as $curso)
        @php
            // Usar la clase ya cargada por eager loading (próxima clase futura)
            $proximaClase = $curso->clases->first();
            $tieneAcceso  = $matricula && $matricula->tieneAcceso();

            $gradients = [
                'pollito'    => 'from-blue-700 via-blue-600 to-blue-400',
                'intermedio' => 'from-[#082B59] via-[#1a5ba0] to-[#30A9D9]',
                'ambos'      => 'from-violet-800 via-violet-600 to-violet-400',
            ];
            $grad = $gradients[$curso->nivel] ?? $gradients['intermedio'];
        @endphp

        <div class="group bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden
                    hover:shadow-2xl hover:-translate-y-2 hover:scale-[1.01]
                    transition-all duration-400 flex flex-col">

            {{-- ── Thumbnail con gradiente e imagen opcional ── --}}
            <div class="relative h-44 bg-gradient-to-br {{ $grad }} overflow-hidden flex-shrink-0">

                @if($curso->imagen_url)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($curso->imagen_url) }}" alt="{{ $curso->nombre }}"
                         class="absolute inset-0 w-full h-full object-cover opacity-50
                                group-hover:opacity-70 group-hover:scale-110 transition-all duration-700">
                @else
                    {{-- Patrón decorativo si no hay imagen --}}
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-24 h-24 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-white/5"></div>
                    <div class="absolute -right-2 bottom-0 w-20 h-20 rounded-full bg-white/5"></div>
                @endif

                {{-- Gradient overlay abajo para legibilidad --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                {{-- Hover overlay --}}
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300"></div>

                {{-- Badges superiores --}}
                <div class="absolute top-3 left-3 flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                                 bg-black/30 backdrop-blur-sm text-white/90 border border-white/10">
                        {{ $curso->nivelLabel() }}
                    </span>
                    @if($curso->grado)
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold
                                     bg-amber-400/25 backdrop-blur-sm text-amber-200">
                            {{ $curso->gradoLabel() }}
                        </span>
                    @endif
                </div>

                {{-- Nombre del curso sobre la imagen --}}
                <div class="absolute bottom-0 left-0 right-0 px-4 pb-4">
                    <h3 class="text-lg font-black text-white leading-tight drop-shadow-lg">{{ $curso->nombre }}</h3>
                    <p class="text-white/60 text-xs mt-0.5">{{ $curso->tipoLabel() }}</p>
                </div>
            </div>

            {{-- ── Cuerpo de la tarjeta ── --}}
            <div class="px-5 pt-4 pb-5 flex flex-col flex-1">

                {{-- Descripción --}}
                @if($curso->descripcion)
                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-4">{{ $curso->descripcion }}</p>
                @else
                    <p class="text-gray-300 text-sm italic mb-4">Contenido en preparación.</p>
                @endif

                @php
                    $claseHoy     = $curso->clases->first(fn($c) => $c->fecha->isToday());
                    $proximaClase = $curso->clases->first(fn($c) => $c->fecha->isFuture());
                    $mostrarClase = $claseHoy ?? $proximaClase;
                @endphp

                {{-- Próxima clase / clase de hoy --}}
                @if($mostrarClase)
                    <div class="flex items-center gap-2.5 mb-4 p-3 rounded-2xl
                                {{ $claseHoy ? 'bg-emerald-50 border border-emerald-100' : 'bg-accent/5 border border-accent/10' }}">
                        <div class="w-8 h-8 rounded-xl {{ $claseHoy ? 'bg-emerald-100' : 'bg-accent/15' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 {{ $claseHoy ? 'text-emerald-600' : 'text-accent' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold {{ $claseHoy ? 'text-emerald-700' : 'text-accent' }} leading-none truncate">
                                {{ $claseHoy ? '¡Clase hoy!' : $mostrarClase->titulo }}
                            </p>
                            <p class="text-[10px] text-gray-400 mt-0.5">
                                {{ \Carbon\Carbon::parse($mostrarClase->fecha)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                @endif

                {{-- Materiales recientes --}}
                @if($curso->materiales->isNotEmpty())
                    <div class="mb-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Materiales recientes</p>
                        <div class="space-y-1">
                            @foreach($curso->materiales->take(3) as $material)
                            @php
                                $tipoConfig = match($material->tipo) {
                                    'pdf'    => ['icon' => '📄', 'color' => 'text-red-500 bg-red-50 border-red-100'],
                                    'video'  => ['icon' => '🎥', 'color' => 'text-violet-500 bg-violet-50 border-violet-100'],
                                    'enlace' => ['icon' => '🔗', 'color' => 'text-blue-500 bg-blue-50 border-blue-100'],
                                    'imagen' => ['icon' => '🖼️', 'color' => 'text-amber-500 bg-amber-50 border-amber-100'],
                                    default  => ['icon' => '📎', 'color' => 'text-gray-500 bg-gray-50 border-gray-100'],
                                };
                            @endphp
                            <a href="{{ $material->url }}" target="_blank"
                               class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-gray-50
                                      transition-colors group/mat border border-transparent hover:border-gray-100">
                                <span class="text-sm flex-shrink-0">{{ $tipoConfig['icon'] }}</span>
                                <span class="text-xs font-medium text-gray-600 truncate group-hover/mat:text-primary-dark transition-colors flex-1">
                                    {{ $material->titulo }}
                                </span>
                                <svg class="w-3 h-3 text-gray-300 group-hover/mat:text-accent flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Spacer para empujar el botón abajo --}}
                <div class="flex-1"></div>

                {{-- ── Botones de acción ── --}}
                @php
                    $diasCortos      = $curso->diasLabels();
                    $zoomHabilitado  = $claseHoy && now()->gte($claseHoy->fecha->copy()->subMinutes(5));
                    $minutosRestantes = $claseHoy && !$zoomHabilitado
                        ? (int) now()->diffInMinutes($claseHoy->fecha->copy()->subMinutes(5), false)
                        : null;
                @endphp
                <div class="flex flex-col gap-2">
                    @if(!$tieneAcceso)
                        @php
                            $waCurso = wa_url(wa_mensaje_renovacion());
                        @endphp
                        <a href="{{ $waCurso }}" target="_blank" rel="noopener"
                           class="w-full flex items-center justify-center gap-2 py-2.5 rounded-2xl font-bold text-sm
                                  bg-[#25D366] hover:bg-[#1ebe5d] text-white shadow-md
                                  hover:-translate-y-0.5 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 flex-shrink-0">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
                            </svg>
                            Renovar membresía · WhatsApp
                        </a>
                    @elseif(!$curso->zoom_link)
                        <button disabled
                                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-2xl font-bold text-sm
                                       bg-gray-100 text-gray-400 cursor-not-allowed">
                            Sin link de Zoom configurado
                        </button>
                    @elseif($zoomHabilitado)
                        {{-- Clase habilitada (5 min antes o durante) → botón activo --}}
                        <a href="{{ route('alumno.zoom', $curso) }}" target="_blank"
                           class="w-full flex items-center justify-center gap-2 py-2.5 rounded-2xl font-bold text-sm text-white
                                  bg-gradient-to-r from-emerald-500 to-teal-400
                                  hover:from-emerald-600 hover:to-teal-500
                                  transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 relative overflow-hidden">
                            <span class="absolute inset-0 bg-white/10 animate-pulse rounded-2xl pointer-events-none"></span>
                            <svg class="w-4 h-4 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span class="relative z-10">¡Entrar a la clase ahora!</span>
                        </a>
                    @elseif($claseHoy)
                        {{-- Clase hoy pero aún no es la hora --}}
                        <button disabled
                                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-2xl font-bold text-sm
                                       bg-emerald-50 text-emerald-500 cursor-not-allowed border border-emerald-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Clase a las {{ $claseHoy->fecha->format('H:i') }}
                            @if($minutosRestantes !== null && $minutosRestantes <= 60)
                                (en {{ $minutosRestantes }} min)
                            @endif
                        </button>
                    @else
                        {{-- Sin clase programada hoy --}}
                        <button disabled
                                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-2xl font-bold text-sm
                                       bg-gray-50 text-gray-400 cursor-not-allowed border border-gray-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $proximaClase ? 'Próx. ' . $proximaClase->fecha->format('d/m H:i') : (implode(' · ', $diasCortos) ?: 'Sin clase programada') }}
                        </button>
                    @endif

                    {{-- Botón secundario: ver detalle del curso --}}
                    <a href="{{ route('alumno.curso-detalle', $curso) }}"
                       class="w-full flex items-center justify-center gap-2 py-2.5 rounded-2xl font-bold text-sm
                              text-primary-dark bg-primary-dark/5 border border-primary-dark/10
                              hover:bg-primary-dark/10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        Ver clases y materiales
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection
