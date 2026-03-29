@extends('layouts.dashboard')
@section('title', 'Asistencia — ' . $clase->titulo)

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('clases.show', $clase) }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('clases.index') }}" class="hover:text-accent transition-colors">Clases</a>
                <span class="mx-1">/</span>
                <a href="{{ route('clases.show', $clase) }}" class="hover:text-accent transition-colors">{{ $clase->titulo }}</a>
                <span class="mx-1">/</span>
                <span class="text-gray-600">Asistencia</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">Registrar Asistencia</h1>
        </div>
    </div>

    {{-- Info clase --}}
    <div class="bg-gradient-to-r from-primary-dark/5 to-accent/5 rounded-2xl border border-primary-dark/10 p-4 mb-5">
        <div class="flex items-center gap-4 flex-wrap">
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide">Clase</p>
                <p class="font-bold text-primary-dark">{{ $clase->titulo }}</p>
            </div>
            <div class="w-px h-8 bg-gray-200 hidden sm:block"></div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide">Curso</p>
                <p class="font-semibold text-gray-700">{{ $clase->curso->nombre }}</p>
            </div>
            <div class="w-px h-8 bg-gray-200 hidden sm:block"></div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide">Fecha</p>
                <p class="font-semibold text-gray-700">{{ $clase->fecha->format('d/m/Y — H:i') }}</p>
            </div>
            <div class="w-px h-8 bg-gray-200 hidden sm:block"></div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide">Alumnos</p>
                <p class="font-bold text-gray-700">{{ $alumnosInscritos->count() }}</p>
            </div>
        </div>
    </div>

    @if($alumnosInscritos->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
            <p class="text-gray-500 font-semibold">No hay alumnos inscritos en este curso</p>
            <p class="text-gray-400 text-sm mt-1">Inscribe alumnos al curso para poder registrar asistencia.</p>
        </div>
    @else
        <form method="POST" action="{{ route('asistencias.guardar', $clase) }}">
            @csrf

            {{-- Selector rápido --}}
            <div x-data class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
                <div class="flex items-center gap-3 flex-wrap">
                    <p class="text-sm font-semibold text-gray-600 mr-2">Marcar todos como:</p>
                    <button type="button" @click="document.querySelectorAll('select[name*=estado]').forEach(s => s.value = 'presente')"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition-colors">
                        Presentes
                    </button>
                    <button type="button" @click="document.querySelectorAll('select[name*=estado]').forEach(s => s.value = 'ausente')"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200 hover:bg-gray-200 transition-colors">
                        Ausentes
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
                <div class="divide-y divide-gray-50">
                    @foreach($alumnosInscritos as $alumno)
                    @php
                        $asistencia   = $asistenciasExistentes->get($alumno->id);
                        $estadoActual = $asistencia?->estado ?? 'presente';
                        $obsActual    = $asistencia?->observacion ?? '';
                        $viaZoom      = $asistencia?->hora_ingreso;
                    @endphp
                    <div x-data="{ estado: '{{ $estadoActual }}' }"
                         class="p-4 transition-colors"
                         :class="{
                             'bg-emerald-50/50': estado === 'presente',
                             'bg-amber-50/30':   estado === 'tardanza',
                             'bg-blue-50/30':    estado === 'justificado',
                             'bg-gray-50/50':    estado === 'ausente'
                         }">
                        <div class="flex items-center gap-4 flex-wrap">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0
                                        {{ $alumno->esIntermedio() ? 'bg-violet-100 text-violet-700' : 'bg-sky-100 text-sky-700' }}">
                                {{ $alumno->inicial() }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-semibold text-gray-800 text-sm">{{ $alumno->nombreCompleto() }}</p>
                                    @if($viaZoom)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold
                                                     bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            Zoom {{ $viaZoom->format('H:i') }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400">{{ $alumno->dni }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <select name="asistencias[{{ $alumno->id }}][estado]"
                                        x-model="estado"
                                        class="px-3 py-2 rounded-xl border border-gray-200 text-sm font-semibold outline-none
                                               focus:border-accent focus:ring-2 focus:ring-accent/20 bg-white cursor-pointer
                                               transition-colors">
                                    <option value="presente">Presente</option>
                                    <option value="tardanza">Tardanza</option>
                                    <option value="justificado">Justificado</option>
                                    <option value="ausente">Ausente</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-2 pl-14" x-show="estado !== 'presente'">
                            <input type="text"
                                   name="asistencias[{{ $alumno->id }}][observacion]"
                                   value="{{ $obsActual }}"
                                   placeholder="Observación (opcional)..."
                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs outline-none
                                          focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white transition-all">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('clases.show', $clase) }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl font-bold text-sm text-white
                               bg-gradient-to-r from-emerald-500 to-green-500
                               hover:from-emerald-600 hover:to-green-600 transition-all duration-300 shadow-md hover:shadow-lg">
                    Guardar asistencia
                </button>
            </div>
        </form>
    @endif

</div>
@endsection
