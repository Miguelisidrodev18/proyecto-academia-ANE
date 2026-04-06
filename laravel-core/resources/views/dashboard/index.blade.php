@extends('layouts.dashboard')

@section('title', 'Inicio')

@section('content')

{{-- ── Bienvenida ────────────────────────────────────────────────────────────── --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl sm:text-2xl font-black text-primary-dark leading-tight">
            ¡Hola, {{ explode(' ', auth()->user()->name)[0] }}! 👋
        </h1>
        <p class="text-gray-400 text-sm mt-0.5">
            {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM YYYY') }} &middot;
            <span class="capitalize font-medium text-primary-light">{{ auth()->user()->role }}</span>
        </p>
    </div>
    @if(auth()->user()->isAdmin())
    <div class="flex items-center gap-2 flex-shrink-0">
        <a href="{{ route('alumnos.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
                  bg-primary-dark text-white hover:bg-primary-dark/90 transition-all duration-150 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo alumno
        </a>
    </div>
    @endif
</div>

{{-- ── Stats ─────────────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">

    {{-- Alumnos --}}
    <a href="{{ route('alumnos.index') }}"
       class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100
              hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
        <div class="flex items-start justify-between mb-3 sm:mb-4">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center
                        group-hover:scale-110 transition-transform flex-shrink-0">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full leading-tight">
                {{ $alumnosActivos }} activos
            </span>
        </div>
        <p class="text-2xl sm:text-3xl font-black text-gray-800 leading-none">{{ $totalAlumnos }}</p>
        <p class="text-xs text-gray-400 font-medium mt-1">Alumnos registrados</p>
    </a>

    {{-- Matrículas --}}
    <a href="{{ route('matriculas.index') }}"
       class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100
              hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
        <div class="flex items-start justify-between mb-3 sm:mb-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center
                        group-hover:scale-110 transition-transform flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full leading-tight">
                Activas
            </span>
        </div>
        <p class="text-2xl sm:text-3xl font-black text-gray-800 leading-none">{{ $matriculasActivas }}</p>
        <p class="text-xs text-gray-400 font-medium mt-1">Matrículas activas</p>
    </a>

    {{-- Ingresos del mes --}}
    <a href="{{ route('pagos.index') }}"
       class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100
              hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
        <div class="flex items-start justify-between mb-3 sm:mb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center
                        group-hover:scale-110 transition-transform flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            @if($pagosPendientes > 0)
                <span class="text-[11px] font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full leading-tight">
                    {{ $pagosPendientes }} pend.
                </span>
            @else
                <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full leading-tight">
                    Al día
                </span>
            @endif
        </div>
        <p class="text-2xl sm:text-3xl font-black text-gray-800 leading-none">
            S/.&nbsp;{{ number_format($ingresosMes, 0) }}
        </p>
        <p class="text-xs text-gray-400 font-medium mt-1">Ingresos este mes</p>
    </a>

    {{-- Módulos activos --}}
    <div class="bg-gradient-to-br from-primary-dark to-primary-light rounded-2xl p-4 sm:p-5 shadow-sm relative overflow-hidden">
        <div class="absolute inset-0 opacity-10"
             style="background-image: radial-gradient(circle at 80% 20%, #0BC4D9 0%, transparent 50%)"></div>
        <div class="relative">
            <div class="flex items-start justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>
                <span class="text-[11px] font-semibold text-white/60 bg-white/10 px-2 py-0.5 rounded-full leading-tight">
                    Sistema
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-black text-white leading-none">6 / 9</p>
            <p class="text-xs text-white/60 font-medium mt-1">Módulos activos</p>
        </div>
    </div>

</div>

{{-- ── Accesos rápidos ───────────────────────────────────────────────────────── --}}
@if(auth()->user()->isAdmin())
<div class="mb-6">
    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Acceso rápido</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">

        @php
        $accesos = [
            ['label' => 'Nuevo alumno',    'ruta' => 'alumnos.create',    'bg' => 'bg-blue-500',   'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
            ['label' => 'Nueva matrícula', 'ruta' => 'matriculas.create', 'bg' => 'bg-indigo-500', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['label' => 'Registrar pago',  'ruta' => 'pagos.create',      'bg' => 'bg-emerald-500','icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
            ['label' => 'Ver alumnos',     'ruta' => 'alumnos.index',     'bg' => 'bg-sky-500',    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => 'Matrículas',      'ruta' => 'matriculas.index',  'bg' => 'bg-violet-500', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['label' => 'Reportes',        'ruta' => 'dashboard.reportes','bg' => 'bg-teal-500',   'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        ];
        @endphp

        @foreach($accesos as $acc)
        <a href="{{ route($acc['ruta']) }}"
           class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100
                  hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group
                  flex flex-col items-center text-center gap-3">
            <div class="w-10 h-10 rounded-xl {{ $acc['bg'] }} flex items-center justify-center
                        group-hover:scale-110 transition-transform duration-200 shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $acc['icon'] }}"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-gray-600 leading-tight">{{ $acc['label'] }}</p>
        </a>
        @endforeach

    </div>
</div>
@endif

{{-- ── Módulos del sistema ───────────────────────────────────────────────────── --}}
<div class="mb-6">
    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Módulos del sistema</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">

        @php
        $modulos = [
            ['nombre' => 'Alumnos',         'ruta' => 'alumnos.index',             'color' => 'blue',   'active' => true,  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['nombre' => 'Matrículas',      'ruta' => 'matriculas.index',          'color' => 'indigo', 'active' => true,  'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['nombre' => 'Pagos',           'ruta' => 'pagos.index',               'color' => 'green',  'active' => true,  'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['nombre' => 'Aula Virtual',    'ruta' => 'cursos.index',              'color' => 'purple', 'active' => true,  'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
            ['nombre' => 'Prospectos',      'ruta' => 'leads.index',               'color' => 'pink',   'active' => true,  'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
            ['nombre' => 'Reportes',        'ruta' => 'dashboard.reportes',        'color' => 'teal',   'active' => true,  'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['nombre' => 'Configuración',   'ruta' => 'dashboard.configuracion',   'color' => 'slate',  'active' => true,  'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ['nombre' => 'Bazar',           'ruta' => 'dashboard.bazar',           'color' => 'orange', 'active' => false, 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
            ['nombre' => 'Reconocimientos', 'ruta' => 'dashboard.reconocimientos', 'color' => 'amber',  'active' => false, 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
        ];

        $colorMap = [
            'blue'   => ['bg' => 'bg-blue-50',   'text' => 'text-blue-500',   'dot' => 'bg-blue-400'],
            'indigo' => ['bg' => 'bg-indigo-50',  'text' => 'text-indigo-500', 'dot' => 'bg-indigo-400'],
            'green'  => ['bg' => 'bg-green-50',   'text' => 'text-green-500',  'dot' => 'bg-green-400'],
            'purple' => ['bg' => 'bg-purple-50',  'text' => 'text-purple-500', 'dot' => 'bg-purple-400'],
            'pink'   => ['bg' => 'bg-pink-50',    'text' => 'text-pink-500',   'dot' => 'bg-pink-400'],
            'teal'   => ['bg' => 'bg-teal-50',    'text' => 'text-teal-500',   'dot' => 'bg-teal-400'],
            'slate'  => ['bg' => 'bg-slate-50',   'text' => 'text-slate-500',  'dot' => 'bg-slate-400'],
            'orange' => ['bg' => 'bg-orange-50',  'text' => 'text-orange-400', 'dot' => 'bg-orange-300'],
            'amber'  => ['bg' => 'bg-amber-50',   'text' => 'text-amber-400',  'dot' => 'bg-amber-300'],
        ];
        @endphp

        @foreach($modulos as $mod)
        @php $c = $colorMap[$mod['color']]; @endphp
        <a href="{{ route($mod['ruta']) }}"
           @class([
               'bg-white rounded-2xl p-4 shadow-sm border transition-all duration-200 group text-center',
               'border-gray-100 hover:shadow-md hover:-translate-y-0.5' => $mod['active'],
               'border-gray-100 opacity-60 cursor-not-allowed pointer-events-none' => !$mod['active'],
           ])>
            <div class="w-11 h-11 rounded-xl {{ $c['bg'] }} flex items-center justify-center mx-auto mb-3
                        {{ $mod['active'] ? 'group-hover:scale-110' : '' }} transition-transform duration-200">
                <svg class="w-5 h-5 {{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $mod['icon'] }}"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-gray-600 leading-tight mb-1.5">{{ $mod['nombre'] }}</p>
            @if($mod['active'])
                <div class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Activo
                </div>
            @else
                <p class="text-[10px] text-gray-400 font-medium">Próximamente</p>
            @endif
        </a>
        @endforeach

    </div>
</div>

{{-- ── Banner estado del sistema ────────────────────────────────────────────── --}}
<div class="bg-gradient-to-r from-primary-dark to-[#1a4a8a] rounded-2xl p-4 sm:p-5
            flex flex-col sm:flex-row items-start sm:items-center gap-4 relative overflow-hidden">

    {{-- Decoración de fondo --}}
    <div class="absolute right-0 top-0 bottom-0 w-48 opacity-10"
         style="background: radial-gradient(circle at 100% 50%, #0BC4D9, transparent 70%)"></div>

    <div class="relative w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>

    <div class="relative flex-1 min-w-0">
        <p class="text-white font-bold text-sm">
            Sistema operativo &middot; 6 módulos activos
        </p>
        <p class="text-white/50 text-xs mt-0.5">
            Alumnos · Matrículas · Pagos · Aula Virtual · Prospectos · Reportes · Configuración
        </p>
    </div>

    <a href="{{ route('dashboard.reportes') }}"
       class="relative flex-shrink-0 text-xs font-semibold text-white/80 hover:text-white
              bg-white/10 hover:bg-white/15 px-4 py-2 rounded-xl transition-all duration-150">
        Ver reportes →
    </a>
</div>

@endsection
