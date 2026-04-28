@extends('layouts.dashboard')
@section('title', 'Panel Representante')

@section('content')

{{-- Overlay de anuncios (fullscreen, una vez al día) --}}
@if($mostrarAnunciosOverlay && $anuncios->isNotEmpty())
    @include('partials.anuncios-overlay')
@endif

@php
    $nombre = explode(' ', auth()->user()->name)[0];
    $hora   = now()->hour;
    $saludo = $hora < 12 ? 'Buenos días' : ($hora < 19 ? 'Buenas tardes' : 'Buenas noches');
@endphp

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ANUNCIOS                                                     --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@include('partials.anuncios-banner')

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- HERO                                                        --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="relative rounded-3xl overflow-hidden mb-6 shadow-xl"
     style="background: linear-gradient(135deg, #082B59 0%, #0f3d7a 40%, #1a5ba0 70%, #30A9D9 100%);">
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/5"></div>
        <div class="absolute bottom-0 left-1/3 w-48 h-48 rounded-full bg-white/3"></div>
    </div>
    <div class="relative z-10 px-6 py-7 md:px-10 flex flex-col md:flex-row md:items-center gap-5">
        <div class="flex-1">
            <p class="text-white/50 text-sm font-medium mb-0.5">{{ $saludo }},</p>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight mb-3">{{ $nombre }}</h1>

            @if($alumno)
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                                 bg-white/15 text-white border border-white/20">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Alumno: {{ $alumno->nombreCompleto() }}
                    </span>
                    @if($matricula)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                                     bg-emerald-400/20 text-emerald-200 border border-emerald-400/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Matrícula activa
                        </span>
                        @if(!$matricula->plan->acceso_ilimitado && $matricula->diasRestantes() <= 7)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold
                                         bg-amber-400/25 text-amber-200 border border-amber-400/30">
                                ⚠️ Vence en {{ $matricula->diasRestantes() }} días
                            </span>
                        @endif
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                                     bg-white/10 text-white/60 border border-white/20">
                            Sin matrícula activa
                        </span>
                    @endif
                </div>
            @else
                <p class="text-amber-300 text-sm">No hay alumno vinculado a tu cuenta · Contacta a la academia</p>
            @endif
        </div>

        {{-- Badge saldo --}}
        @if($matricula && $matricula->saldoPendiente() > 0)
        <div class="flex-shrink-0 bg-amber-400/20 border border-amber-400/40 rounded-2xl px-5 py-4 text-center backdrop-blur-sm">
            <p class="text-amber-200 text-[10px] font-bold uppercase tracking-wide mb-1">Saldo pendiente</p>
            <p class="text-white font-black text-2xl leading-none">S/. {{ number_format($matricula->saldoPendiente(), 2) }}</p>
        </div>
        @elseif($matricula && $matricula->saldoPendiente() <= 0)
        <div class="flex-shrink-0 bg-emerald-400/20 border border-emerald-400/40 rounded-2xl px-5 py-4 text-center backdrop-blur-sm">
            <p class="text-emerald-200 text-[10px] font-bold uppercase tracking-wide mb-1">Al día</p>
            <p class="text-white font-black text-lg leading-none">✓ Pagado</p>
        </div>
        @endif
    </div>
</div>

@if(!$alumno)
{{-- Sin alumno vinculado --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-16 text-center">
    <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
    </div>
    <p class="text-gray-700 font-bold">Tu cuenta no está vinculada a ningún alumno</p>
    <p class="text-gray-400 text-sm mt-1 max-w-xs mx-auto">Comunícate con la academia para que vinculen tu cuenta al alumno correspondiente.</p>
</div>

@else

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- RESUMEN DE PAGOS                                            --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($matricula)
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    {{-- Total pagado --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total pagado</p>
            <p class="text-xl font-black text-emerald-700">S/. {{ number_format($matricula->totalPagado(), 2) }}</p>
        </div>
    </div>

    {{-- Saldo pendiente --}}
    <div class="bg-white rounded-2xl border shadow-sm p-5 flex items-center gap-4
                {{ $matricula->saldoPendiente() > 0 ? 'border-amber-200' : 'border-gray-100' }}">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                    {{ $matricula->saldoPendiente() > 0 ? 'bg-amber-50' : 'bg-gray-50' }}">
            <svg class="w-6 h-6 {{ $matricula->saldoPendiente() > 0 ? 'text-amber-500' : 'text-gray-400' }}"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Saldo pendiente</p>
            <p class="text-xl font-black {{ $matricula->saldoPendiente() > 0 ? 'text-amber-600' : 'text-gray-400' }}">
                S/. {{ number_format($matricula->saldoPendiente(), 2) }}
            </p>
        </div>
    </div>

    {{-- Días restantes --}}
    <div class="bg-white rounded-2xl border shadow-sm p-5 flex items-center gap-4
                {{ !$matricula->plan->acceso_ilimitado && $matricula->diasRestantes() <= 7 ? 'border-red-200' : 'border-gray-100' }}">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                    {{ !$matricula->plan->acceso_ilimitado && $matricula->diasRestantes() <= 7 ? 'bg-red-50' : 'bg-primary-dark/5' }}">
            <svg class="w-6 h-6 {{ !$matricula->plan->acceso_ilimitado && $matricula->diasRestantes() <= 7 ? 'text-red-500' : 'text-primary-dark' }}"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Vencimiento</p>
            @if($matricula->plan->acceso_ilimitado)
                <p class="text-xl font-black text-primary-dark">Ilimitado</p>
            @else
                <p class="text-xl font-black {{ $matricula->diasRestantes() <= 7 ? 'text-red-600' : 'text-primary-dark' }}">
                    {{ $matricula->diasRestantes() }} días
                </p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $matricula->fecha_fin->format('d/m/Y') }}</p>
            @endif
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ALERTA PAGOS PENDIENTES                                     --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@php
    $cuotasVencidas  = $cuotas->filter(fn($c) => $c->esVencida() || $c->estaVencidaSinPagar());
    $cuotasPendientes = $cuotas->filter(fn($c) => $c->esPendiente() && !$c->estaVencidaSinPagar());
@endphp

@if($cuotasVencidas->isNotEmpty())
<div class="rounded-2xl overflow-hidden shadow-md mb-6
            bg-gradient-to-r from-red-600 to-orange-500">
    <div class="px-5 py-4 flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-white font-black text-sm">
                ⚠️ {{ $cuotasVencidas->count() }} {{ $cuotasVencidas->count() === 1 ? 'cuota vencida' : 'cuotas vencidas' }} sin pagar
            </p>
            <p class="text-white/85 text-xs mt-1">
                El acceso al aula virtual del alumno puede quedar bloqueado. Regulariza los pagos a la brevedad posible.
            </p>
        </div>
        <div class="flex-shrink-0 bg-white/20 rounded-xl px-3 py-2 text-center">
            <p class="text-white font-black text-lg leading-none">{{ $cuotasVencidas->count() }}</p>
            <p class="text-white/70 text-[10px]">{{ $cuotasVencidas->count() === 1 ? 'cuota' : 'cuotas' }}</p>
        </div>
    </div>
</div>
@elseif(!$matricula->plan->acceso_ilimitado && $matricula->diasRestantes() <= 7)
<div class="rounded-2xl overflow-hidden shadow-md mb-6
            bg-gradient-to-r from-amber-500 to-yellow-400">
    <div class="px-5 py-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-white/25 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-white font-black text-sm">La matrícula vence en {{ $matricula->diasRestantes() }} días</p>
            <p class="text-white/85 text-xs mt-1">Renueva a tiempo para que el alumno no pierda acceso a sus clases.</p>
        </div>
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- CUOTAS / ESTADO DE CUENTA                                   --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($cuotas->isNotEmpty())
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-sm font-black text-primary-dark flex items-center gap-2">
            <span class="w-1 h-4 rounded-full bg-accent inline-block"></span>
            Estado de cuenta · {{ $matricula->plan->nombre }}
        </h2>
        <span class="text-xs text-gray-400">Precio total: <strong class="text-gray-700">S/. {{ number_format($matricula->precio_pagado, 2) }}</strong></span>
    </div>
    <div class="divide-y divide-gray-50">
        @foreach($cuotas as $cuota)
        @php
            $esVencida = $cuota->esVencida() || $cuota->estaVencidaSinPagar();
        @endphp
        <div class="flex items-center justify-between px-5 py-3.5
                    {{ $esVencida ? 'bg-red-50/50' : '' }}">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-xs font-black
                            {{ $cuota->esPagada() ? 'bg-emerald-100 text-emerald-700' : ($esVencida ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                    {{ $cuota->numero }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Cuota #{{ $cuota->numero }}</p>
                    <p class="text-xs text-gray-400">
                        Vence: {{ $cuota->fecha_vencimiento->format('d/m/Y') }}
                        @if($cuota->esPagada() && $cuota->fecha_pago)
                            · Pagada: {{ $cuota->fecha_pago->format('d/m/Y') }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <p class="font-black text-sm {{ $cuota->esPagada() ? 'text-emerald-700' : ($esVencida ? 'text-red-700' : 'text-amber-700') }}">
                    S/. {{ number_format($cuota->monto, 2) }}
                </p>
                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full border {{ $cuota->estadoBadgeClass() }}">
                    {{ $cuota->estadoLabel() }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- HISTORIAL DE PAGOS + PRÓXIMAS CLASES                        --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    {{-- Historial de pagos --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-black text-primary-dark flex items-center gap-2">
                <span class="w-1 h-4 rounded-full bg-emerald-500 inline-block"></span>
                Historial de pagos
            </h2>
        </div>
        @if($pagos->isEmpty())
        <div class="py-10 text-center text-gray-400 text-sm">
            <p>Sin pagos registrados aún</p>
        </div>
        @else
        <div class="divide-y divide-gray-50">
            @foreach($pagos as $pago)
            <div class="flex items-center justify-between px-5 py-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                {{ $pago->estaConfirmado() ? 'bg-emerald-50' : 'bg-amber-50' }}">
                        @if($pago->estaConfirmado())
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">{{ $pago->fecha_pago?->format('d/m/Y') ?? '—' }}</p>
                        <p class="text-xs text-gray-400">
                            {{ ucfirst($pago->metodo_pago ?? 'Efectivo') }}
                            @if($pago->referencia) · Ref: {{ $pago->referencia }} @endif
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-black text-sm {{ $pago->estaConfirmado() ? 'text-emerald-700' : 'text-amber-600' }}">
                        S/. {{ number_format($pago->monto, 2) }}
                    </p>
                    <p class="text-[11px] font-semibold {{ $pago->estaConfirmado() ? 'text-emerald-500' : 'text-amber-500' }}">
                        {{ $pago->estaConfirmado() ? 'Confirmado' : 'Pendiente' }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Próximas clases --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-black text-primary-dark flex items-center gap-2">
                <span class="w-1 h-4 rounded-full bg-primary-light inline-block"></span>
                Próximas clases
            </h2>
        </div>
        @if($proximasClases->isEmpty())
        <div class="py-10 text-center text-gray-400 text-sm">
            <p>No hay clases programadas próximamente</p>
        </div>
        @else
        <div class="divide-y divide-gray-50">
            @foreach($proximasClases as $clase)
            @php
                $dias      = now()->startOfDay()->diffInDays($clase->fecha->startOfDay(), false);
                $estaHoy   = $dias === 0;
                $esManana  = $dias === 1;
            @endphp
            <div class="flex items-center gap-3 px-5 py-3.5">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-center
                            {{ $estaHoy ? 'bg-emerald-500' : 'bg-primary-dark/10' }}">
                    @if($estaHoy)
                        <span class="text-white font-black text-xs">HOY</span>
                    @else
                        <span class="font-black text-primary-dark text-sm leading-none">{{ $clase->fecha->format('d') }}</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-400 font-semibold truncate">{{ $clase->curso->nombre }}</p>
                    <p class="text-sm font-bold text-gray-700 truncate">{{ $clase->titulo }}</p>
                    <p class="text-xs text-gray-400">
                        {{ $estaHoy ? 'Hoy' : ($esManana ? 'Mañana' : $clase->fecha->format('d/m/Y')) }}
                        · {{ $clase->fecha->format('H:i') }}
                    </p>
                </div>
                @if($estaHoy)
                    <span class="flex-shrink-0 flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>Hoy
                    </span>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- INFO DEL ALUMNO                                             --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
    <h2 class="text-sm font-black text-primary-dark flex items-center gap-2 mb-4">
        <span class="w-1 h-4 rounded-full bg-violet-500 inline-block"></span>
        Información del alumno
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Nombre</p>
            <p class="text-sm font-bold text-gray-700 truncate">{{ $alumno->nombreCompleto() }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">DNI</p>
            <p class="text-sm font-mono font-bold text-gray-700">{{ $alumno->dni }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Nivel</p>
            <p class="text-sm font-bold text-gray-700">{{ $alumno->tipo === 'pollito' ? '🐣 Pollito' : '⚡ Intermedio' }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Racha</p>
            <p class="text-sm font-bold text-gray-700">
                {{ $alumno->racha_actual }} {{ $alumno->racha_actual > 0 ? '🔥' : '' }}
                <span class="font-normal text-gray-400">días</span>
            </p>
        </div>
        @if($matricula)
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Plan</p>
            <p class="text-sm font-bold text-gray-700 truncate">{{ $matricula->plan->nombre }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Inicio</p>
            <p class="text-sm font-bold text-gray-700">{{ $matricula->fecha_inicio?->format('d/m/Y') ?? '—' }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Fin</p>
            <p class="text-sm font-bold text-gray-700">
                {{ $matricula->plan->acceso_ilimitado ? 'Sin límite' : ($matricula->fecha_fin?->format('d/m/Y') ?? '—') }}
            </p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Acceso</p>
            @if($matricula->tieneAcceso())
                <p class="text-sm font-bold text-emerald-600 flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Activo
                </p>
            @else
                <p class="text-sm font-bold text-red-600 flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span> Bloqueado
                </p>
            @endif
        </div>
        @endif
    </div>
</div>

@else
{{-- Sin matrícula --}}
<div class="bg-white rounded-2xl border border-amber-200 shadow-sm py-14 text-center">
    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
        </svg>
    </div>
    <p class="text-gray-700 font-bold">{{ $alumno->nombreCompleto() }} no tiene matrícula activa</p>
    <p class="text-gray-400 text-sm mt-1 max-w-xs mx-auto">Comunícate con la academia para gestionar la matrícula.</p>
</div>
@endif

@endif

@endsection
