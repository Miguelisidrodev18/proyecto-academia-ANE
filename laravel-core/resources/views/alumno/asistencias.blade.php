@extends('layouts.dashboard')
@section('title', 'Mis Asistencias')

@section('content')

@php $nombre = explode(' ', auth()->user()->name)[0]; @endphp

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Mis Asistencias</h1>
        <p class="text-gray-400 text-sm mt-0.5">Hola {{ $nombre }}, aquí puedes revisar tu historial de clases</p>
    </div>
</div>

@if(!$alumno)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-center gap-4">
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
@else

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
        <p class="text-3xl font-black text-primary-dark leading-none">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-400 mt-1.5 font-semibold uppercase tracking-wide">Total clases</p>
    </div>
    <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm p-4 text-center">
        <p class="text-3xl font-black text-emerald-600 leading-none">{{ $stats['presentes'] }}</p>
        <p class="text-xs text-gray-400 mt-1.5 font-semibold uppercase tracking-wide">Presentes</p>
        @if($stats['total'] > 0)
            <p class="text-xs text-emerald-500 font-bold mt-1">{{ round($stats['presentes'] / $stats['total'] * 100) }}%</p>
        @endif
    </div>
    <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-4 text-center">
        <p class="text-3xl font-black text-amber-600 leading-none">{{ $stats['tardanzas'] }}</p>
        <p class="text-xs text-gray-400 mt-1.5 font-semibold uppercase tracking-wide">Tardanzas</p>
    </div>
    <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-4 text-center">
        <p class="text-3xl font-black text-red-500 leading-none">{{ $stats['ausentes'] }}</p>
        <p class="text-xs text-gray-400 mt-1.5 font-semibold uppercase tracking-wide">Ausentes</p>
    </div>
</div>

{{-- Barra de progreso global --}}
@if($stats['total'] > 0)
@php
    $pctPresente    = round($stats['presentes']    / $stats['total'] * 100);
    $pctTardanza    = round($stats['tardanzas']    / $stats['total'] * 100);
    $pctJustificado = round($stats['justificados'] / $stats['total'] * 100);
    $pctAusente     = round($stats['ausentes']     / $stats['total'] * 100);
@endphp
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
    <div class="flex items-center justify-between mb-3">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Porcentaje de asistencia</p>
        <p class="text-sm font-black {{ $pctPresente >= 75 ? 'text-emerald-600' : ($pctPresente >= 50 ? 'text-amber-600' : 'text-red-600') }}">
            {{ $pctPresente }}% asistencia
        </p>
    </div>
    <div class="w-full h-3 rounded-full bg-gray-100 overflow-hidden flex">
        @if($pctPresente > 0)
            <div class="h-full bg-emerald-400 transition-all" style="width: {{ $pctPresente }}%"></div>
        @endif
        @if($pctTardanza > 0)
            <div class="h-full bg-amber-400 transition-all" style="width: {{ $pctTardanza }}%"></div>
        @endif
        @if($pctJustificado > 0)
            <div class="h-full bg-blue-400 transition-all" style="width: {{ $pctJustificado }}%"></div>
        @endif
        @if($pctAusente > 0)
            <div class="h-full bg-red-300 transition-all" style="width: {{ $pctAusente }}%"></div>
        @endif
    </div>
    <div class="flex flex-wrap gap-4 mt-3">
        <span class="flex items-center gap-1.5 text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>Presente {{ $pctPresente }}%</span>
        @if($pctTardanza > 0)
        <span class="flex items-center gap-1.5 text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>Tardanza {{ $pctTardanza }}%</span>
        @endif
        @if($pctJustificado > 0)
        <span class="flex items-center gap-1.5 text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>Justificado {{ $pctJustificado }}%</span>
        @endif
        @if($pctAusente > 0)
        <span class="flex items-center gap-1.5 text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-red-300"></span>Ausente {{ $pctAusente }}%</span>
        @endif
    </div>
</div>
@endif

{{-- Historial --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-primary-dark/10 flex items-center justify-center">
            <svg class="w-3.5 h-3.5 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h2 class="text-sm font-bold text-gray-700">Historial de clases</h2>
        <span class="ml-auto text-xs text-gray-400">{{ $asistencias->count() }} {{ $asistencias->count() === 1 ? 'registro' : 'registros' }}</span>
    </div>

    @if($asistencias->isEmpty())
        <div class="py-20 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <p class="text-gray-500 font-semibold">Aún no tienes registros de asistencia</p>
            <p class="text-gray-400 text-sm mt-1">Aparecerán aquí cuando el docente registre tus clases.</p>
        </div>
    @else
        {{-- Desktop --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left">
                        <th class="px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Clase</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Curso</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Fecha</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Hora ingreso</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asistencias as $asistencia)
                    @php
                        $estadoConfig = match($asistencia->estado) {
                            'presente'    => ['label' => 'Presente',    'class' => 'bg-emerald-100 text-emerald-700', 'dot' => 'bg-emerald-500'],
                            'tardanza'    => ['label' => 'Tardanza',    'class' => 'bg-amber-100 text-amber-700',   'dot' => 'bg-amber-500'],
                            'justificado' => ['label' => 'Justificado', 'class' => 'bg-blue-100 text-blue-700',     'dot' => 'bg-blue-500'],
                            default       => ['label' => 'Ausente',     'class' => 'bg-red-50 text-red-500',        'dot' => 'bg-red-400'],
                        };
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition-colors
                               {{ $asistencia->estado === 'ausente' ? 'opacity-75' : '' }}">
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-gray-700 text-sm">{{ $asistencia->clase->titulo }}</p>
                            @if($asistencia->observacion)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $asistencia->observacion }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="text-sm text-gray-600">{{ $asistencia->clase->curso->nombre }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($asistencia->clase->fecha)->format('d/m/Y') }}
                            <br>
                            <span class="text-gray-400">{{ \Carbon\Carbon::parse($asistencia->clase->fecha)->diffForHumans() }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($asistencia->hora_ingreso)
                                <span class="inline-flex items-center gap-1 text-xs text-emerald-600 font-semibold">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $asistencia->hora_ingreso->format('H:i') }}
                                </span>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $estadoConfig['class'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $estadoConfig['dot'] }}"></span>
                                {{ $estadoConfig['label'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="md:hidden divide-y divide-gray-50">
            @foreach($asistencias as $asistencia)
            @php
                $estadoConfig = match($asistencia->estado) {
                    'presente'    => ['label' => 'Presente',    'class' => 'bg-emerald-100 text-emerald-700', 'dot' => 'bg-emerald-500'],
                    'tardanza'    => ['label' => 'Tardanza',    'class' => 'bg-amber-100 text-amber-700',   'dot' => 'bg-amber-500'],
                    'justificado' => ['label' => 'Justificado', 'class' => 'bg-blue-100 text-blue-700',     'dot' => 'bg-blue-500'],
                    default       => ['label' => 'Ausente',     'class' => 'bg-red-50 text-red-500',        'dot' => 'bg-red-400'],
                };
            @endphp
            <div class="p-4 {{ $asistencia->estado === 'ausente' ? 'opacity-75' : '' }}">
                <div class="flex items-start justify-between gap-3 mb-1">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-700 text-sm truncate">{{ $asistencia->clase->titulo }}</p>
                        <p class="text-xs text-gray-400">{{ $asistencia->clase->curso->nombre }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold {{ $estadoConfig['class'] }} flex-shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full {{ $estadoConfig['dot'] }}"></span>
                        {{ $estadoConfig['label'] }}
                    </span>
                </div>
                <div class="flex items-center gap-3 mt-1.5">
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($asistencia->clase->fecha)->format('d/m/Y') }}</p>
                    @if($asistencia->hora_ingreso)
                        <span class="text-xs text-emerald-600 font-semibold flex items-center gap-0.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $asistencia->hora_ingreso->format('H:i') }}
                        </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

@endif

@endsection
