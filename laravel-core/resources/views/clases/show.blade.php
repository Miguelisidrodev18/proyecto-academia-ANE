@extends('layouts.dashboard')
@section('title', $clase->titulo)

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Breadcrumb + acciones --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('cursos.show', $clase->curso) }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('cursos.index') }}" class="hover:text-accent transition-colors">Cursos</a>
                <span class="mx-1">/</span>
                <a href="{{ route('cursos.show', $clase->curso) }}" class="hover:text-accent transition-colors">{{ $clase->curso->nombre }}</a>
                <span class="mx-1">/</span>
                <span class="text-gray-600">{{ $clase->titulo }}</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">{{ $clase->titulo }}</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('asistencias.registrar', $clase) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-semibold text-sm
                      border border-emerald-200 text-emerald-700 hover:bg-emerald-50 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Asistencia
            </a>
            <a href="{{ route('clases.edit', $clase) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-semibold text-sm
                      bg-primary-dark text-white hover:bg-accent transition-all shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
        </div>
    </div>

    @include('clases._flash')

    {{-- Hero card --}}
    @php $esPasada = $clase->fecha->isPast(); @endphp
    <div class="relative bg-gradient-to-br from-primary-dark via-[#0e3d7a] to-[#30A9D9] rounded-3xl p-6 mb-5 text-white overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

        <div class="relative flex items-start gap-5 flex-wrap">
            <div class="w-16 h-16 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 flex-wrap mb-1">
                    <h2 class="text-xl font-black">{{ $clase->titulo }}</h2>
                    @if(!$esPasada)
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-400/20 text-emerald-200 border border-emerald-400/30">Próxima</span>
                    @else
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-white/10 text-white/60 border border-white/20">Realizada</span>
                    @endif
                </div>
                <p class="text-white/70 text-sm">{{ $clase->curso->nombre }}</p>
                <p class="text-white/50 text-xs mt-1">{{ $clase->fecha->format('d \d\e F \d\e Y — H:i') }}</p>
            </div>
            @if($clase->asistencias->count() > 0)
            <div class="text-center bg-white/10 backdrop-blur rounded-2xl px-5 py-3 border border-white/20">
                <p class="text-2xl font-black">{{ $clase->porcentajeAsistencia() }}%</p>
                <p class="text-white/60 text-xs mt-0.5">asistencia</p>
                <p class="text-white/50 text-xs">{{ $clase->totalPresentes() }}/{{ $clase->asistencias->count() }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Links y descripción --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Accesos</h3>
            <div class="space-y-3">
                @if($clase->curso->zoom_link)
                <a href="{{ $clase->curso->zoom_link }}" target="_blank"
                   class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 border border-blue-100 hover:bg-blue-100 transition-colors group">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-blue-700">Enlace de Zoom (del curso)</p>
                        <p class="text-xs text-blue-500 truncate">{{ $clase->curso->zoom_link }}</p>
                    </div>
                    <svg class="w-3.5 h-3.5 text-blue-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
                @else
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-xs text-gray-400">Sin link de Zoom configurado en el curso</p>
                </div>
                @endif

                @if($clase->grabacion_url)
                <x-youtube-player url="{{ $clase->grabacion_url }}" label="{{ $clase->titulo }} — Grabación">
                    <span class="flex items-center gap-3 p-3 rounded-xl bg-violet-50 border border-violet-100 hover:bg-violet-100 transition-colors group w-full">
                        <span class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <span class="flex-1 min-w-0">
                            <span class="block text-xs font-bold text-violet-700">Grabación disponible</span>
                            <span class="block text-xs text-violet-500 truncate">{{ $clase->grabacion_url }}</span>
                        </span>
                        <svg class="w-3.5 h-3.5 text-violet-400 group-hover:text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </span>
                </x-youtube-player>
                @else
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-xs text-gray-400">Sin grabación aún</p>
                </div>
                @endif
            </div>
        </div>

        @if($clase->descripcion)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Descripción</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $clase->descripcion }}</p>
        </div>
        @endif
    </div>

    {{-- Asistencia --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-emerald-100 flex items-center justify-center">
                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </span>
                Asistencia
            </h3>
            <a href="{{ route('asistencias.registrar', $clase) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold
                      bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition-colors">
                {{ $clase->asistencias->count() > 0 ? 'Editar asistencia' : 'Registrar asistencia' }}
            </a>
        </div>

        @if($clase->asistencias->isEmpty())
            <div class="text-center py-8">
                <p class="text-sm text-gray-400">Aún no se ha registrado asistencia para esta clase.</p>
                <p class="text-xs text-gray-400 mt-1">El curso tiene {{ $alumnosInscritos->count() }} alumno(s) inscrito(s).</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($clase->asistencias as $asistencia)
                <div class="flex items-center justify-between p-3 rounded-xl
                            {{ $asistencia->esPresente() ? 'bg-emerald-50 border border-emerald-100' : 'bg-gray-50 border border-gray-100' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs
                                    {{ $asistencia->alumno->esIntermedio() ? 'bg-violet-100 text-violet-700' : 'bg-sky-100 text-sky-700' }}">
                            {{ $asistencia->alumno->inicial() }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{ $asistencia->alumno->nombreCompleto() }}</p>
                            @if($asistencia->hora_ingreso)
                                <p class="text-xs text-emerald-600 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Ingresó {{ $asistencia->hora_ingreso->format('H:i') }}
                                </p>
                            @elseif($asistencia->observacion)
                                <p class="text-xs text-gray-400">{{ $asistencia->observacion }}</p>
                            @endif
                        </div>
                    </div>
                    @php
                        $estadoClasses = match($asistencia->estado) {
                            'presente'    => 'bg-emerald-100 text-emerald-700',
                            'tardanza'    => 'bg-amber-100 text-amber-700',
                            'justificado' => 'bg-blue-100 text-blue-700',
                            default       => 'bg-gray-200 text-gray-500',
                        };
                        $estadoLabel = match($asistencia->estado) {
                            'presente'    => 'Presente',
                            'tardanza'    => 'Tardanza',
                            'justificado' => 'Justificado',
                            default       => 'Ausente',
                        };
                    @endphp
                    <div class="flex items-center gap-2">
                        @if($asistencia->hora_ingreso)
                            <span class="text-[10px] text-emerald-500 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                vía Zoom
                            </span>
                        @endif
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $estadoClasses }}">
                            {{ $estadoLabel }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
