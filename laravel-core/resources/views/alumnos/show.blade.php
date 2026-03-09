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
            <a href="{{ route('alumnos.edit', $alumno) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm
                      border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
            <a href="{{ route('alumnos.documentos', $alumno) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm text-white
                      bg-gradient-to-r from-primary-dark to-primary-light hover:from-accent hover:to-secondary
                      transition-all duration-300 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Documentos
            </a>
        </div>
    </div>

    @include('alumnos._flash')

    {{-- Tarjeta de perfil --}}
    <div class="bg-gradient-to-br from-primary-dark to-[#30A9D9] rounded-2xl p-6 mb-5 text-white">
        <div class="flex items-center gap-5 flex-wrap">
            {{-- Avatar --}}
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center font-black text-3xl flex-shrink-0
                        {{ $alumno->esPremium() ? 'bg-amber-400/30 text-amber-200' : 'bg-white/20 text-white' }}">
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

    {{-- Datos personales --}}
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
                        <p class="text-sm font-semibold text-gray-700">{{ $alumno->email }}</p>
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
                @if($alumno->direccion)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Dirección</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $alumno->direccion }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Información académica</h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">DNI</p>
                        <p class="text-sm font-mono font-bold text-gray-700">{{ $alumno->dni }}</p>
                    </div>
                </div>
                @if($alumno->fecha_nacimiento)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Fecha de nacimiento</p>
                        <p class="text-sm font-semibold text-gray-700">
                            {{ $alumno->fecha_nacimiento->format('d/m/Y') }}
                            <span class="text-gray-400 text-xs">({{ $alumno->fecha_nacimiento->age }} años)</span>
                        </p>
                    </div>
                </div>
                @endif
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Documentos</p>
                        <p class="text-sm font-semibold text-gray-700">
                            {{ count($alumno->documentos ?? []) }} archivo(s) subidos
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Preview documentos --}}
    @if(!empty($alumno->documentos))
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Documentos</h3>
            <a href="{{ route('alumnos.documentos', $alumno) }}"
               class="text-xs text-accent font-semibold hover:underline">
                Gestionar →
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @foreach($alumno->documentos as $doc)
            <div class="flex items-center gap-2 p-3 rounded-xl bg-gray-50 border border-gray-100">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                            {{ in_array($doc['extension'], ['jpg','jpeg','png']) ? 'bg-blue-100 text-blue-600' : 'bg-red-100 text-red-600' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-700 truncate">{{ $doc['nombre'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ strtoupper($doc['extension']) }} · {{ $doc['fecha'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection
