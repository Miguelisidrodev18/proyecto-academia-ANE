@extends('layouts.dashboard')
@section('title', $curso->nombre)

@section('content')

@php
    $gradients = [
        'pollito'    => 'from-blue-700 via-blue-600 to-blue-400',
        'intermedio' => 'from-[#082B59] via-[#1a5ba0] to-[#30A9D9]',
        'ambos'      => 'from-violet-800 via-violet-600 to-violet-400',
    ];
    $grad = $gradients[$curso->nivel] ?? $gradients['intermedio'];
    $tieneAcceso = $matricula && $matricula->tieneAcceso();

    $clasesProximas  = $curso->clases->where('fecha', '>', now())->sortBy('fecha');
    $clasesPasadas   = $curso->clases->where('fecha', '<=', now())->sortByDesc('fecha');
@endphp

{{-- ── Header / breadcrumb ── --}}
<div class="flex items-center gap-3 mb-6 flex-wrap">
    <a href="{{ route('alumno.mis-cursos') }}"
       class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div class="flex-1">
        <nav class="text-xs text-gray-400 mb-0.5">
            <a href="{{ route('alumno.mis-cursos') }}" class="hover:text-accent transition-colors">Mis Cursos</a>
            <span class="mx-1">/</span>
            <span class="text-gray-600">{{ $curso->nombre }}</span>
        </nav>
        <h1 class="text-xl font-black text-primary-dark leading-none">{{ $curso->nombre }}</h1>
    </div>
</div>

{{-- ── Banner del curso ── --}}
<div class="relative rounded-3xl overflow-hidden mb-6 shadow-lg">
    <div class="bg-gradient-to-r {{ $grad }} px-6 py-8">
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-white/5"></div>
            <div class="absolute -bottom-8 right-24 w-32 h-32 rounded-full bg-white/5"></div>
        </div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                                 bg-white/20 text-white border border-white/20">
                        {{ $curso->nivelLabel() }}
                    </span>
                    @if($curso->grado)
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-400/25 text-amber-200">
                            {{ $curso->gradoLabel() }}
                        </span>
                    @endif
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/10 text-white/70">
                        {{ $curso->tipoLabel() }}
                    </span>
                </div>
                <h2 class="text-2xl font-black text-white">{{ $curso->nombre }}</h2>
                @if($curso->descripcion)
                    <p class="text-white/70 text-sm mt-1 leading-relaxed">{{ $curso->descripcion }}</p>
                @endif
            </div>
            <div class="flex flex-col items-center gap-1 flex-shrink-0">
                <span class="text-3xl font-black text-white">{{ $curso->clases->count() }}</span>
                <span class="text-white/60 text-xs">{{ $curso->clases->count() === 1 ? 'clase' : 'clases' }}</span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── Columna principal: clases ── --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Próximas clases --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-accent"></span>
                    Próximas clases
                </h3>
                <span class="text-xs text-gray-400">{{ $clasesProximas->count() }} programadas</span>
            </div>
            @if($clasesProximas->isEmpty())
                <div class="py-10 text-center">
                    <p class="text-gray-400 text-sm">No hay clases próximas programadas.</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($clasesProximas as $clase)
                    <div class="px-5 py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-700 truncate">{{ $clase->titulo }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($clase->fecha)->format('d/m/Y') }}
                                    · {{ \Carbon\Carbon::parse($clase->fecha)->format('H:i') }}
                                </p>
                            </div>
                        </div>
                        @if($tieneAcceso && $clase->zoom_link)
                            <a href="{{ $clase->zoom_link }}" target="_blank"
                               class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                                      bg-gradient-to-r from-primary-dark to-primary-light text-white text-xs font-bold
                                      hover:from-accent hover:to-secondary transition-all shadow-sm hover:shadow-md">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Entrar
                            </a>
                        @elseif(!$tieneAcceso)
                            <span class="flex-shrink-0 text-xs text-red-400 font-semibold">Acceso suspendido</span>
                        @else
                            <span class="flex-shrink-0 text-xs text-gray-300 font-medium">Sin enlace</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Clases pasadas / grabadas --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                    Clases anteriores
                </h3>
                <span class="text-xs text-gray-400">{{ $clasesPasadas->count() }} realizadas</span>
            </div>
            @if($clasesPasadas->isEmpty())
                <div class="py-10 text-center">
                    <p class="text-gray-400 text-sm">Aún no se han realizado clases.</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($clasesPasadas as $clase)
                    <div class="px-5 py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-600 truncate">{{ $clase->titulo }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($clase->fecha)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @if($clase->grabada && $clase->grabacion_url)
                            <a href="{{ $clase->grabacion_url }}" target="_blank"
                               class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                                      bg-violet-50 text-violet-600 border border-violet-100 text-xs font-bold
                                      hover:bg-violet-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Ver grabación
                            </a>
                        @else
                            <span class="flex-shrink-0 text-xs text-gray-300 font-medium">Sin grabación</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ── Columna lateral: materiales ── --}}
    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-700">Materiales del curso</h3>
            </div>
            @if($curso->materiales->isEmpty())
                <div class="py-10 text-center px-4">
                    <p class="text-gray-400 text-sm">No hay materiales disponibles aún.</p>
                </div>
            @else
                <div class="p-3 space-y-1">
                    @foreach($curso->materiales as $material)
                    @php
                        $tipoConfig = match($material->tipo) {
                            'pdf'    => ['icon' => '📄', 'color' => 'bg-red-50 border-red-100 text-red-500'],
                            'video'  => ['icon' => '🎥', 'color' => 'bg-violet-50 border-violet-100 text-violet-500'],
                            'enlace' => ['icon' => '🔗', 'color' => 'bg-blue-50 border-blue-100 text-blue-500'],
                            'imagen' => ['icon' => '🖼️', 'color' => 'bg-amber-50 border-amber-100 text-amber-500'],
                            default  => ['icon' => '📎', 'color' => 'bg-gray-50 border-gray-100 text-gray-500'],
                        };
                    @endphp
                    <a href="{{ $material->url }}" target="_blank"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50
                              border border-transparent hover:border-gray-100 transition-colors group">
                        <span class="text-base flex-shrink-0">{{ $tipoConfig['icon'] }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-gray-700 truncate group-hover:text-primary-dark transition-colors">
                                {{ $material->titulo }}
                            </p>
                            @if($material->fecha_publicacion)
                                <p class="text-[10px] text-gray-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($material->fecha_publicacion)->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                        <svg class="w-3 h-3 text-gray-300 group-hover:text-accent flex-shrink-0 transition-colors"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info del plan --}}
        @if($matricula)
        <div class="bg-primary-dark/5 rounded-2xl border border-primary-dark/10 p-4">
            <p class="text-xs font-bold text-primary-dark uppercase tracking-wide mb-1">Tu plan</p>
            <p class="text-sm font-black text-primary-dark">{{ $matricula->plan->nombre }}</p>
            <p class="text-xs text-gray-500 mt-1">
                @if($matricula->plan->acceso_ilimitado)
                    Acceso ilimitado
                @else
                    Vence: {{ \Carbon\Carbon::parse($matricula->fecha_fin)->format('d/m/Y') }}
                @endif
            </p>
        </div>
        @endif
    </div>

</div>

@endsection
