@extends('layouts.dashboard')

@section('title', 'Inicio')

@section('content')

{{-- Bienvenida --}}
<div class="mb-6">
    <h1 class="text-2xl font-black text-primary-dark">
        ¡Bienvenido, {{ explode(' ', auth()->user()->name)[0] }}! 👋
    </h1>
    <p class="text-gray-500 text-sm mt-1">
        Panel de control — Academia Nueva Era &middot;
        <span class="capitalize font-medium text-primary-light">{{ auth()->user()->role }}</span>
    </p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    {{-- Alumnos --}}
    <a href="{{ route('alumnos.index') }}"
       class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 block">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Activo</span>
        </div>
        <p class="text-3xl font-black text-gray-800">{{ $totalAlumnos }}</p>
        <p class="text-xs text-gray-400 font-medium mt-0.5">Alumnos registrados</p>
    </a>

    {{-- Matrículas --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-indigo-400 bg-indigo-50 px-2 py-0.5 rounded-full">Pronto</span>
        </div>
        <p class="text-3xl font-black text-gray-800">0</p>
        <p class="text-xs text-gray-400 font-medium mt-0.5">Matrículas activas</p>
    </div>

    {{-- Pagos --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-green-400 bg-green-50 px-2 py-0.5 rounded-full">Pronto</span>
        </div>
        <p class="text-3xl font-black text-gray-800">S/. 0</p>
        <p class="text-xs text-gray-400 font-medium mt-0.5">Ingresos del mes</p>
    </div>

    {{-- Módulos --}}
    <div class="bg-gradient-to-br from-[#082B59] to-[#30A9D9] rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-black text-white">1 / 10</p>
        <p class="text-xs text-white/60 font-medium mt-0.5">Módulos activos</p>
    </div>

</div>

{{-- Grid de módulos --}}
<h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Módulos del sistema</h2>
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">

    @php
    $modulos = [
        ['nombre' => 'Alumnos',          'ruta' => 'alumnos.index',             'bg' => 'bg-blue-50',   'text' => 'text-blue-600',   'active' => true,  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
        ['nombre' => 'Matrículas',       'ruta' => 'dashboard.matriculas',      'bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'active' => false, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>'],
        ['nombre' => 'Pagos',            'ruta' => 'dashboard.pagos',           'bg' => 'bg-green-50',  'text' => 'text-green-600',  'active' => false, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        ['nombre' => 'Asistencia',       'ruta' => 'dashboard.asistencia',      'bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'active' => false, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>'],
        ['nombre' => 'Aula Virtual',     'ruta' => 'dashboard.aula-virtual',    'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'active' => false, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>'],
        ['nombre' => 'Bazar',            'ruta' => 'dashboard.bazar',           'bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'active' => false, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>'],
        ['nombre' => 'Reconocimientos',  'ruta' => 'dashboard.reconocimientos', 'bg' => 'bg-amber-50',  'text' => 'text-amber-600',  'active' => false, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
        ['nombre' => 'Reportes',         'ruta' => 'dashboard.reportes',        'bg' => 'bg-teal-50',   'text' => 'text-teal-600',   'active' => false, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
        ['nombre' => 'Configuración',    'ruta' => 'dashboard.configuracion',   'bg' => 'bg-slate-50',  'text' => 'text-slate-600',  'active' => false, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
    ];
    @endphp

    @foreach($modulos as $mod)
    <a href="{{ route($mod['ruta']) }}"
       class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100
              hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group text-center">
        <div class="w-12 h-12 rounded-xl {{ $mod['bg'] }} flex items-center justify-center mx-auto mb-3
                    group-hover:scale-110 transition-transform duration-200">
            <svg class="w-6 h-6 {{ $mod['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $mod['icon'] !!}
            </svg>
        </div>
        <p class="text-xs font-bold text-gray-600 leading-tight">{{ $mod['nombre'] }}</p>
        @if($mod['active'])
            <p class="text-[10px] text-green-500 mt-1 font-bold">Activo</p>
        @else
            <p class="text-[10px] text-gray-400 mt-1 font-medium">Próximamente</p>
        @endif
    </a>
    @endforeach

</div>

{{-- Banner informativo --}}
<div class="mt-6 bg-gradient-to-r from-[#082B59] to-[#30A9D9] rounded-2xl p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6 text-[#0BC4D9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <div>
        <p class="text-white font-bold text-sm">Sistema en construcción</p>
        <p class="text-white/60 text-xs mt-0.5">
            Los módulos se irán habilitando progresivamente. Actualmente puedes gestionar usuarios y explorar el panel.
        </p>
    </div>
</div>

@endsection
