@extends('layouts.dashboard')
@section('title', $curso->nombre)

@section('content')

<div class="max-w-5xl mx-auto">

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
                Editar curso
            </a>
        </div>
        @endif
    </div>

    @include('cursos._flash')

    {{-- Layout de dos columnas --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Columna izquierda: Info + Alumnos --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Info card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r
                    {{ $curso->nivel === 'pollito' ? 'from-blue-600 to-blue-400' : ($curso->nivel === 'intermedio' ? 'from-primary-dark to-primary-light' : 'from-violet-700 to-violet-500') }}
                    px-5 py-5">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-white/60 text-[10px] font-semibold uppercase tracking-widest mb-1">{{ $curso->nivelLabel() }}</p>
                            <h2 class="text-lg font-black text-white leading-tight">{{ $curso->nombre }}</h2>
                            <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                <span class="text-white/80 text-xs">{{ $curso->tipoLabel() }}</span>
                                @if($curso->grado)
                                    <span class="text-white/80 text-xs">· {{ $curso->gradoLabel() }}</span>
                                @endif
                            </div>
                        </div>
                        <span @class([
                            'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold flex-shrink-0',
                            'bg-emerald-400/20 text-emerald-200 border border-emerald-400/30' => $curso->activo,
                            'bg-white/10 text-white/50 border border-white/20'                => !$curso->activo,
                        ])>
                            <span class="w-1.5 h-1.5 rounded-full {{ $curso->activo ? 'bg-emerald-400' : 'bg-white/40' }}"></span>
                            {{ $curso->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>

                <div class="p-5 space-y-4">
                    @if($curso->descripcion)
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $curso->descripcion }}</p>
                    @else
                        <p class="text-gray-400 italic text-sm">Sin descripción.</p>
                    @endif

                    {{-- Zoom link --}}
                    @if($curso->zoom_link)
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Link de Zoom</p>
                            <a href="{{ $curso->zoom_link }}" target="_blank"
                               class="flex items-center gap-2 text-sm text-accent font-semibold hover:underline break-all">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Abrir Zoom
                            </a>
                        </div>
                    @endif

                    {{-- Días y hora --}}
                    @if(!empty($curso->dias_semana) || $curso->hora_inicio)
                        <div class="pt-3 border-t border-gray-100">
                            @if(!empty($curso->dias_semana))
                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-2">Días de clase</p>
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach(\App\Models\Curso::ordenDias() as $dia)
                                        @php $esActivo = in_array($dia, $curso->dias_semana ?? []); @endphp
                                        <span @class([
                                            'inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-black',
                                            'bg-accent text-white shadow-sm shadow-accent/30' => $esActivo,
                                            'bg-gray-100 text-gray-300' => !$esActivo,
                                        ])>
                                            {{ ['lunes'=>'Lu','martes'=>'Ma','miercoles'=>'Mi','jueves'=>'Ju','viernes'=>'Vi','sabado'=>'Sá','domingo'=>'Do'][$dia] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            @if($curso->hora_inicio)
                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Hora de inicio</p>
                                <p class="text-sm font-bold text-gray-700">{{ \Carbon\Carbon::parse($curso->hora_inicio)->format('H:i') }}</p>
                            @endif
                        </div>
                    @endif

                    {{-- Planes --}}
                    @if($curso->planes->isNotEmpty())
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-2">Planes</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($curso->planes as $plan)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                                 bg-primary-dark/5 text-primary-dark border border-primary-dark/10">
                                        {{ $plan->nombre }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Alumnos inscritos --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-700">Alumnos inscritos</h3>
                    <span class="text-xs text-gray-400">{{ $alumnos->total() }}</span>
                </div>
                @if($alumnos->isEmpty())
                    <div class="py-8 text-center">
                        <p class="text-gray-400 text-sm">Sin alumnos inscritos.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($alumnos as $alumno)
                        <div class="px-5 py-2.5 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-dark to-primary-light
                                            flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">
                                    {{ strtoupper(substr($alumno->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">{{ $alumno->user->name ?? '—' }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $alumno->user->email ?? '' }}</p>
                                </div>
                            </div>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('alumnos.show', $alumno) }}"
                                   class="text-[10px] text-accent hover:underline font-medium">Ver</a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @if($alumnos->hasPages())
                        <div class="px-5 py-3 border-t border-gray-50 flex items-center justify-between">
                            @if($alumnos->onFirstPage())
                                <span class="text-[10px] text-gray-300 font-medium">← Anterior</span>
                            @else
                                <a href="{{ $alumnos->previousPageUrl() }}&{{ http_build_query(request()->except('alumnos')) }}"
                                   class="text-[10px] text-accent font-bold hover:underline">← Anterior</a>
                            @endif
                            <span class="text-[10px] text-gray-400">
                                Pág. {{ $alumnos->currentPage() }} / {{ $alumnos->lastPage() }}
                            </span>
                            @if($alumnos->hasMorePages())
                                <a href="{{ $alumnos->nextPageUrl() }}&{{ http_build_query(request()->except('alumnos')) }}"
                                   class="text-[10px] text-accent font-bold hover:underline">Siguiente →</a>
                            @else
                                <span class="text-[10px] text-gray-300 font-medium">Siguiente →</span>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- Columna derecha: Clases + Materiales --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- ── CLASES ─────────────────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Clases
                    </h3>
                    <span class="text-xs text-gray-400">{{ $curso->clases->count() }} registradas</span>
                </div>

                {{-- Formulario rápido nueva clase --}}
                <div class="px-5 py-4 bg-gray-50 border-b border-gray-100" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 text-xs font-bold text-accent hover:text-primary-dark transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nueva clase
                    </button>
                    <div x-show="open" x-transition class="mt-3">
                        <form action="{{ route('cursos.clases.store', $curso) }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="sm:col-span-2">
                                    <input type="text" name="titulo" placeholder="Título de la clase *"
                                           value="{{ old('titulo') }}"
                                           class="w-full text-sm px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent/30 focus:border-accent">
                                </div>
                                <div>
                                    <input type="datetime-local" name="fecha"
                                           value="{{ old('fecha') }}"
                                           class="w-full text-sm px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent/30 focus:border-accent">
                                </div>
                                <div>
                                    <input type="text" name="descripcion" placeholder="Descripción (opcional)"
                                           value="{{ old('descripcion') }}"
                                           class="w-full text-sm px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent/30 focus:border-accent">
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit"
                                        class="px-4 py-1.5 rounded-xl bg-accent text-white text-xs font-bold hover:bg-primary-dark transition-colors shadow-sm">
                                    Registrar clase
                                </button>
                                <button type="button" @click="open = false"
                                        class="px-4 py-1.5 rounded-xl border border-gray-200 text-gray-500 text-xs font-medium hover:bg-gray-100 transition-colors">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Lista de clases --}}
                @if($curso->clases->isEmpty())
                    <div class="py-10 text-center">
                        <p class="text-gray-400 text-sm">No hay clases registradas aún.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($curso->clases as $clase)
                        @php $esPasada = $clase->fecha->isPast(); @endphp
                        <div class="px-5 py-3" x-data="{ showGrab: false, showMats: {{ $clase->materiales->isNotEmpty() ? 'true' : 'false' }}, showAddMat: false }">

                            {{-- Fila principal de la clase --}}
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3 flex-1 min-w-0">
                                    <div @class([
                                        'w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5',
                                        'bg-emerald-100' => !$esPasada,
                                        'bg-gray-100'    => $esPasada,
                                    ])>
                                        <svg @class([
                                            'w-4 h-4',
                                            'text-emerald-600' => !$esPasada,
                                            'text-gray-400'    => $esPasada,
                                        ]) fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-700 truncate">{{ $clase->titulo }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $clase->fecha->format('d/m/Y H:i') }}</p>
                                        <div class="flex flex-wrap items-center gap-2 mt-1">
                                            @if($clase->grabacion_url)
                                                <a href="{{ $clase->grabacion_url }}" target="_blank"
                                                   class="inline-flex items-center gap-1 text-[10px] font-bold text-violet-600 hover:underline">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Ver grabación
                                                </a>
                                            @endif
                                            {{-- Badge materiales de clase --}}
                                            @if($clase->materiales->isNotEmpty())
                                                <button @click="showMats = !showMats" type="button"
                                                        class="inline-flex items-center gap-1 text-[10px] font-bold text-accent hover:text-primary-dark transition-colors">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                    </svg>
                                                    {{ $clase->materiales->count() }} material(es)
                                                    <svg class="w-2.5 h-2.5 transition-transform" :class="showMats ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 flex-shrink-0 flex-wrap justify-end">
                                    <a href="{{ route('clases.show', $clase) }}"
                                       class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-primary-dark/5 text-primary-dark border border-primary-dark/10 hover:bg-primary-dark/10 transition-colors">
                                        Ver
                                    </a>
                                    {{-- Botón agregar material de clase --}}
                                    <button @click="showAddMat = !showAddMat; showGrab = false" type="button"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-accent/10 text-accent border border-accent/20 hover:bg-accent/20 transition-colors">
                                        + Material
                                    </button>
                                    @if($esPasada && !$clase->grabacion_url)
                                        <button @click="showGrab = !showGrab; showAddMat = false" type="button"
                                                class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-violet-50 text-violet-600 border border-violet-100 hover:bg-violet-100 transition-colors">
                                            + Grabación
                                        </button>
                                    @endif
                                    <a href="{{ route('asistencias.registrar', $clase) }}"
                                       class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 hover:bg-emerald-100 transition-colors">
                                        Asistencia
                                    </a>
                                    <a href="{{ route('clases.edit', $clase) }}"
                                       class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-gray-50 text-gray-500 border border-gray-200 hover:bg-gray-100 transition-colors">
                                        Editar
                                    </a>
                                    <form action="{{ route('clases.destroy', $clase) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar esta clase?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-red-50 text-red-500 border border-red-100 hover:bg-red-100 transition-colors">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Panel: agregar material de clase (link Drive) --}}
                            <div x-show="showAddMat" x-transition x-cloak class="mt-3 ml-11 p-3 bg-accent/5 border border-accent/15 rounded-xl">
                                <p class="text-[10px] font-bold text-accent uppercase tracking-wide mb-2">Agregar material a esta clase</p>
                                <form action="{{ route('clases.materiales.store', $clase) }}" method="POST" class="space-y-2">
                                    @csrf
                                    <input type="text" name="titulo" placeholder="Título del material *" required
                                           class="w-full text-sm px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent/30 focus:border-accent">
                                    <input type="url" name="url" placeholder="Link de Google Drive / YouTube / etc. *" required
                                           class="w-full text-sm px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent/30 focus:border-accent">
                                    <input type="text" name="descripcion" placeholder="Descripción (opcional)"
                                           class="w-full text-sm px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent/30 focus:border-accent">
                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                                class="px-4 py-1.5 rounded-xl bg-accent text-white text-xs font-bold hover:bg-primary-dark transition-colors shadow-sm">
                                            Guardar material
                                        </button>
                                        <button type="button" @click="showAddMat = false"
                                                class="px-4 py-1.5 rounded-xl border border-gray-200 text-gray-500 text-xs font-medium hover:bg-gray-100 transition-colors">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- Panel: materiales de esta clase --}}
                            <div x-show="showMats" x-transition x-cloak class="mt-2 ml-11 space-y-1">
                                @foreach($clase->materiales as $mat)
                                <div class="flex items-center justify-between gap-2 px-3 py-2 bg-gray-50 rounded-xl border border-gray-100">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <svg class="w-3.5 h-3.5 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                        </svg>
                                        <a href="{{ $mat->url }}" target="_blank"
                                           class="text-xs font-semibold text-gray-700 hover:text-accent transition-colors truncate">
                                            {{ $mat->titulo }}
                                        </a>
                                        @if($mat->descripcion)
                                            <span class="text-[10px] text-gray-400 truncate hidden sm:inline">— {{ $mat->descripcion }}</span>
                                        @endif
                                    </div>
                                    <form action="{{ route('materiales.destroy', $mat) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar este material?')" class="flex-shrink-0">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-[10px] text-red-400 hover:text-red-600 font-bold px-1.5 py-0.5 rounded hover:bg-red-50 transition-colors">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>

                            {{-- Panel: agregar grabación rápida --}}
                            <div x-show="showGrab" x-transition x-cloak class="mt-3 ml-11">
                                <form action="{{ route('clases.grabacion', $clase) }}" method="POST" class="flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <input type="url" name="grabacion_url" placeholder="URL de la grabación *" required
                                           class="flex-1 text-sm px-3 py-1.5 rounded-xl border border-violet-200 focus:outline-none focus:ring-2 focus:ring-violet-300 focus:border-violet-400">
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-xl bg-violet-600 text-white text-xs font-bold hover:bg-violet-700 transition-colors">
                                        Guardar
                                    </button>
                                    <button type="button" @click="showGrab = false"
                                            class="px-3 py-1.5 rounded-xl border border-gray-200 text-gray-500 text-xs hover:bg-gray-50 transition-colors">
                                        ✕
                                    </button>
                                </form>
                            </div>

                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── MATERIALES ──────────────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Materiales
                    </h3>
                    <span class="text-xs text-gray-400">{{ $curso->materiales->count() }} archivos</span>
                </div>

                {{-- Formulario rápido nuevo material --}}
                <div class="px-5 py-4 bg-gray-50 border-b border-gray-100" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 text-xs font-bold text-accent hover:text-primary-dark transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nuevo material
                    </button>
                    <div x-show="open" x-transition class="mt-3">
                        <form action="{{ route('cursos.materiales.store', $curso) }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="sm:col-span-2">
                                    <input type="text" name="titulo" placeholder="Título del material *"
                                           value="{{ old('titulo') }}"
                                           class="w-full text-sm px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent/30 focus:border-accent">
                                </div>
                                <div>
                                    <select name="tipo"
                                            class="w-full text-sm px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent/30 focus:border-accent">
                                        <option value="">Tipo *</option>
                                        <option value="pdf">PDF</option>
                                        <option value="video">Video</option>
                                        <option value="enlace">Enlace</option>
                                        <option value="imagen">Imagen</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                <div>
                                    <input type="url" name="url" placeholder="URL del material *"
                                           value="{{ old('url') }}"
                                           class="w-full text-sm px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent/30 focus:border-accent">
                                </div>
                                <div class="sm:col-span-2">
                                    <input type="text" name="descripcion" placeholder="Descripción (opcional)"
                                           value="{{ old('descripcion') }}"
                                           class="w-full text-sm px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent/30 focus:border-accent">
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit"
                                        class="px-4 py-1.5 rounded-xl bg-accent text-white text-xs font-bold hover:bg-primary-dark transition-colors shadow-sm">
                                    Agregar material
                                </button>
                                <button type="button" @click="open = false"
                                        class="px-4 py-1.5 rounded-xl border border-gray-200 text-gray-500 text-xs font-medium hover:bg-gray-100 transition-colors">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Lista de materiales --}}
                @php
                    $tipoIconos = [
                        'pdf'    => ['color' => 'red',    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        'video'  => ['color' => 'violet', 'icon' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'enlace' => ['color' => 'blue',   'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
                        'imagen' => ['color' => 'green',  'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        'otro'   => ['color' => 'gray',   'icon' => 'M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13'],
                    ];
                @endphp
                @if($curso->materiales->isEmpty())
                    <div class="py-10 text-center">
                        <p class="text-gray-400 text-sm">No hay materiales registrados.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($curso->materiales as $material)
                        @php $ti = $tipoIconos[$material->tipo] ?? $tipoIconos['otro']; @endphp
                        <div class="px-5 py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-{{ $ti['color'] }}-50 border border-{{ $ti['color'] }}-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-{{ $ti['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ti['icon'] }}"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ $material->url }}" target="_blank"
                                       class="text-sm font-semibold text-gray-700 hover:text-accent transition-colors truncate block">
                                        {{ $material->titulo }}
                                    </a>
                                    <p class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($material->fecha_publicacion)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 flex-shrink-0">
                                <form action="{{ route('materiales.toggle', $material) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" @class([
                                        'text-[10px] font-bold px-2.5 py-1 rounded-lg border transition-colors',
                                        'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-100' => $material->visible,
                                        'bg-gray-50 text-gray-400 border-gray-200 hover:bg-gray-100'             => !$material->visible,
                                    ])>
                                        {{ $material->visible ? 'Visible' : 'Oculto' }}
                                    </button>
                                </form>
                                <a href="{{ route('materiales.edit', $material) }}"
                                   class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-gray-50 text-gray-500 border border-gray-200 hover:bg-gray-100 transition-colors">
                                    Editar
                                </a>
                                <form action="{{ route('materiales.destroy', $material) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar este material?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-red-50 text-red-500 border border-red-100 hover:bg-red-100 transition-colors">
                                        Eliminar
                                    </button>
                                </form>
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
