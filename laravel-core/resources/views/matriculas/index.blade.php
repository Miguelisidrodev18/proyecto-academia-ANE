@extends('layouts.dashboard')
@section('title', 'Matrículas')

@section('content')
<div x-data="waModal()">

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats->total ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total matrículas</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats->activas ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Activas</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-orange-400 to-red-400 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats->vencidas ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Vencidas</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats->pendientes ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Pendientes</p>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex items-center justify-between mb-5 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Matrículas</h1>
        <p class="text-gray-400 text-sm mt-0.5">
            {{ $matriculas->total() }} {{ $matriculas->total() === 1 ? 'matrícula registrada' : 'matrículas registradas' }}
        </p>
    </div>
    <a href="{{ route('matriculas.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
              bg-gradient-to-r from-primary-dark to-primary-light
              hover:from-accent hover:to-secondary transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Matrícula
    </a>
</div>

@include('matriculas._flash')

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- RECORDATORIOS DE PAGO                                       --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($recordatorios->isNotEmpty())
<div class="mb-5" x-data="{ abierto: true }">
    <div class="bg-amber-50 border border-amber-200 rounded-2xl overflow-hidden shadow-sm">

        {{-- Header colapsable --}}
        <button type="button" @click="abierto = !abierto"
                class="w-full px-5 py-3.5 flex items-center justify-between gap-3 hover:bg-amber-100/60 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-400/30 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-amber-600">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <p class="text-sm font-black text-amber-800">
                        Recordatorios de pago
                        <span class="ml-1.5 px-2 py-0.5 bg-amber-400 text-white text-xs font-bold rounded-full">{{ $recordatorios->count() }}</span>
                    </p>
                    <p class="text-xs text-amber-600">Alumnos con cuotas vencidas y saldo pendiente por cobrar</p>
                </div>
            </div>
            <svg class="w-4 h-4 text-amber-500 transition-transform duration-200" :class="abierto ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        {{-- Lista de recordatorios --}}
        <div x-show="abierto" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="border-t border-amber-200 divide-y divide-amber-100">
            @foreach($recordatorios as $rec)
            @php
                $tel = preg_replace('/\D/', '', $rec['whatsapp']);
                $tieneWa = strlen($tel) >= 9;
            @endphp
            <div class="px-5 py-3 flex items-center justify-between gap-4 hover:bg-amber-100/40 transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-xl bg-amber-400/20 flex items-center justify-center flex-shrink-0 text-xs font-black text-amber-700">
                        {{ substr($rec['nombre'], 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $rec['nombre'] }}</p>
                        <p class="text-xs text-amber-600">
                            @if($rec['vencidas'] > 0)
                                <span class="font-bold text-red-600">{{ $rec['vencidas'] }} cuota(s) vencida(s)</span>
                                @if($rec['cuotas'] > $rec['vencidas']) · @endif
                            @endif
                            @if($rec['cuotas'] > $rec['vencidas'])
                                {{ $rec['cuotas'] - $rec['vencidas'] }} próxima(s)
                            @endif
                            · Saldo: <span class="font-bold">S/. {{ number_format($rec['saldo'], 2) }}</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if($tieneWa)
                        <button type="button"
                                @click="abrir(
                                    '{{ addslashes($rec['nombre']) }}',
                                    '{{ $tel }}',
                                    '{{ number_format($rec['saldo'], 2) }}',
                                    '{{ $rec['matricula']->diasRestantes() }}',
                                    '{{ addslashes($rec['matricula']->alumno?->user?->email ?? '') }}'
                                )"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold
                                       bg-[#25D366] hover:bg-[#1ebe5d] text-white shadow-sm transition-all hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
                            </svg>
                            Enviar recordatorio
                        </button>
                    @else
                        <span class="text-xs text-gray-400 italic">Sin WhatsApp registrado</span>
                        <a href="{{ route('matriculas.show', $rec['matricula']) }}"
                           class="text-xs text-accent hover:underline">Ver matrícula</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Filtros --}}
<form method="GET" action="{{ route('matriculas.index') }}"
      class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">

        <div class="relative sm:col-span-2"
             x-data="{
                 ph: 'Buscar por nombre del alumno...',
                 idx: 0,
                 opts: ['Buscar por nombre del alumno...', 'Buscar por DNI...', 'Buscar por plan...'],
                 init() { setInterval(() => { this.idx = (this.idx + 1) % this.opts.length; this.ph = this.opts[this.idx]; }, 2500); }
             }">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   :placeholder="ph"
                   class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm
                          focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition-all bg-gray-50 focus:bg-white">
        </div>

        <select name="plan_id"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                       focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
            <option value="">Todos los planes</option>
            @foreach($planes as $plan)
                <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                    {{ $plan->nombre }}
                </option>
            @endforeach
        </select>

        <select name="estado"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                       focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
            <option value="">Todos los estados</option>
            <option value="activa"     {{ request('estado') === 'activa'     ? 'selected' : '' }}>✅ Activa</option>
            <option value="vencida"    {{ request('estado') === 'vencida'    ? 'selected' : '' }}>🔴 Vencida</option>
            <option value="suspendida" {{ request('estado') === 'suspendida' ? 'selected' : '' }}>⏸️ Suspendida</option>
            <option value="pendiente"  {{ request('estado') === 'pendiente'  ? 'selected' : '' }}>🟡 Pendiente</option>
        </select>

        {{-- Fila 2: Ordenar + botones --}}
        <div class="sm:col-span-4 flex gap-2 flex-wrap sm:flex-nowrap">
            <select name="ordenar"
                    class="flex-1 min-w-0 px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                           focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
                <option value="reciente"  {{ request('ordenar', 'reciente') === 'reciente'  ? 'selected' : '' }}>🕐 Más reciente primero</option>
                <option value="dias_asc"  {{ request('ordenar') === 'dias_asc'  ? 'selected' : '' }}>⬆️ Días rest. menor → mayor</option>
                <option value="dias_desc" {{ request('ordenar') === 'dias_desc' ? 'selected' : '' }}>⬇️ Días rest. mayor → menor</option>
                <option value="nombre"    {{ request('ordenar') === 'nombre'    ? 'selected' : '' }}>🔤 Nombre A → Z</option>
            </select>
            <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-primary-dark text-white text-sm font-semibold
                           hover:bg-accent transition-colors flex-shrink-0">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar','plan_id','estado']) || (request('ordenar') && request('ordenar') !== 'reciente'))
                <a href="{{ route('matriculas.index') }}"
                   class="px-3 py-2.5 rounded-xl border border-gray-200 text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-colors flex-shrink-0"
                   title="Limpiar filtros">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            @endif
        </div>
    </div>
</form>

{{-- Tabla --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    @if($matriculas->isEmpty())
        <div class="py-20 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="text-gray-500 font-semibold text-base">No se encontraron matrículas</p>
            <p class="text-gray-400 text-sm mt-1">
                @if(request()->hasAny(['buscar','plan_id','estado']))
                    <a href="{{ route('matriculas.index') }}" class="text-accent font-semibold hover:underline">Limpiar filtros</a>
                @else
                    <a href="{{ route('matriculas.create') }}" class="text-accent font-semibold hover:underline">Registrar la primera matrícula</a>
                @endif
            </p>
        </div>
    @else
        {{-- Desktop --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left bg-gradient-to-r from-gray-50 to-white">
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">#</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Alumno</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Plan</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Precio</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">T. Pago</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Vigencia</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">
                            @php
                                $nextOrden = request('ordenar') === 'dias_asc' ? 'dias_desc' : 'dias_asc';
                                $iconoDias = request('ordenar') === 'dias_asc' ? '↑' : (request('ordenar') === 'dias_desc' ? '↓' : '↕');
                            @endphp
                            <a href="{{ route('matriculas.index', array_merge(request()->query(), ['ordenar' => $nextOrden])) }}"
                               class="inline-flex items-center gap-1 hover:text-accent transition-colors {{ in_array(request('ordenar'), ['dias_asc','dias_desc']) ? 'text-accent' : '' }}">
                                Días rest. <span>{{ $iconoDias }}</span>
                            </a>
                        </th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matriculas as $matricula)
                    @php $dias = $matricula->diasRestantes(); @endphp
                    <tr class="border-b border-gray-50 hover:bg-gradient-to-r hover:from-accent/5 hover:to-transparent transition-all duration-150 group">
                        <td class="px-5 py-4 text-gray-400 text-xs font-mono">{{ $matricula->id }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center
                                            text-xs font-black text-white flex-shrink-0 shadow-sm">
                                    {{ $matricula->alumno?->inicial() ?? '?' }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 leading-none group-hover:text-primary-dark transition-colors">{{ $matricula->alumno?->nombreCompleto() ?? '(alumno eliminado)' }}</p>
                                    <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $matricula->alumno?->dni ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-gray-700 font-semibold">{{ $matricula->plan->nombre }}</p>
                        </td>
                        <td class="px-5 py-4 font-mono text-gray-700 font-semibold">S/. {{ number_format($matricula->precio_pagado, 2) }}</td>
                        <td class="px-5 py-4">
                            @php
                                $tipoBadge = match($matricula->tipo_pago) {
                                    'mensual' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                    'cuotas'  => 'bg-violet-50 text-violet-700 border border-violet-200',
                                    default   => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                };
                                $tipoLabel = match($matricula->tipo_pago) {
                                    'mensual' => 'Mensual',
                                    'cuotas'  => 'Cuotas',
                                    default   => 'Completo',
                                };
                            @endphp
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $tipoBadge }}">{{ $tipoLabel }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-xs text-gray-500">{{ $matricula->fecha_inicio?->format('d/m/Y') ?? '—' }}</p>
                            <p class="text-xs text-gray-400">→ {{ $matricula->fecha_fin?->format('d/m/Y') ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $diaColor = $dias <= 0 ? 'text-red-600' : ($dias <= 7 ? 'text-red-500' : ($dias <= 30 ? 'text-amber-600' : 'text-gray-700'));
                            @endphp
                            <span class="font-black text-base {{ $diaColor }}">{{ max(0, $dias) }}</span>
                            @if($dias > 0 && $dias <= 7)
                                <span class="ml-1 text-xs text-red-400">⚠️</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @include('matriculas._badge', ['estado' => $matricula->estado])
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $waAlumno = $matricula->alumno?->whatsapp ?? $matricula->alumno?->telefono ?? '';
                                $waTel = preg_replace('/\D/', '', $waAlumno);
                                $tieneWaAccion = strlen($waTel) >= 9;
                            @endphp
                            <div class="flex items-center justify-end gap-1">
                                {{-- WhatsApp --}}
                                @if($tieneWaAccion)
                                <button type="button"
                                        @click="abrir(
                                            '{{ addslashes($matricula->alumno?->nombreCompleto() ?? '') }}',
                                            '{{ $waTel }}',
                                            '{{ number_format($matricula->saldoPendiente(), 2) }}',
                                            '{{ $matricula->diasRestantes() }}',
                                            '{{ addslashes($matricula->alumno?->user?->email ?? '') }}'
                                        )"
                                        class="p-2 rounded-lg text-gray-400 hover:text-[#25D366] hover:bg-[#25D366]/10 transition-all"
                                        title="Enviar WhatsApp">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
                                    </svg>
                                </button>
                                @endif
                                <a href="{{ route('matriculas.show', $matricula) }}"
                                   class="p-2 rounded-lg text-gray-400 hover:text-accent hover:bg-accent/10 transition-all" title="Ver">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('matriculas.edit', $matricula) }}"
                                   class="p-2 rounded-lg text-gray-400 hover:text-primary-light hover:bg-primary-light/10 transition-all" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('matriculas.destroy', $matricula) }}"
                                      onsubmit="return confirm('¿Eliminar esta matrícula?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden divide-y divide-gray-50">
            @foreach($matriculas as $matricula)
            @php $dias = $matricula->diasRestantes(); @endphp
            <div class="p-4 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $matricula->alumno?->nombreCompleto() ?? '(alumno eliminado)' }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $matricula->alumno?->dni ?? '—' }}</p>
                        <p class="text-xs text-primary-dark font-semibold mt-0.5">{{ $matricula->plan->nombre }}</p>
                    </div>
                    @include('matriculas._badge', ['estado' => $matricula->estado])
                </div>
                <div class="flex items-center gap-3 text-xs mb-3">
                    <span class="font-mono font-bold text-gray-700">S/. {{ number_format($matricula->precio_pagado, 2) }}</span>
                    <span class="text-gray-400">{{ $matricula->fecha_inicio?->format('d/m/Y') }} → {{ $matricula->fecha_fin?->format('d/m/Y') }}</span>
                    <span class="font-black {{ $dias <= 7 ? 'text-red-500' : 'text-gray-600' }}">{{ max(0, $dias) }}d</span>
                </div>
                <div class="flex gap-2">
                    @php
                        $waMobile = preg_replace('/\D/', '', $matricula->alumno?->whatsapp ?? $matricula->alumno?->telefono ?? '');
                    @endphp
                    @if(strlen($waMobile) >= 9)
                    <button type="button"
                            @click="abrir(
                                '{{ addslashes($matricula->alumno?->nombreCompleto() ?? '') }}',
                                '{{ $waMobile }}',
                                '{{ number_format($matricula->saldoPendiente(), 2) }}',
                                '{{ $matricula->diasRestantes() }}',
                                '{{ addslashes($matricula->alumno?->user?->email ?? '') }}'
                            )"
                            class="py-2 px-3 rounded-xl text-xs font-bold bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white transition-all flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
                        </svg>
                        WA
                    </button>
                    @endif
                    <a href="{{ route('matriculas.show', $matricula) }}"
                       class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-accent/10 text-accent hover:bg-accent hover:text-white transition-all">Ver</a>
                    <a href="{{ route('matriculas.edit', $matricula) }}"
                       class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">Editar</a>
                </div>
            </div>
            @endforeach
        </div>

        @if($matriculas->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $matriculas->links() }}
        </div>
        @endif
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- MODAL WHATSAPP                                                       --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div x-show="show" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="show = false"></div>

    {{-- Panel --}}
    <div class="relative z-10 w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0">

        {{-- Header --}}
        <div class="px-6 py-5 bg-gradient-to-r from-[#075E54] to-[#128C7E] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-black text-sm">Enviar mensaje por WhatsApp</p>
                    <p class="text-white/70 text-xs" x-text="nombre"></p>
                </div>
            </div>
            <button @click="show = false" class="w-8 h-8 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Cuerpo --}}
        <div class="p-6">

            {{-- Info alumno --}}
            <div class="flex items-center gap-3 mb-5 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center text-white font-black text-sm flex-shrink-0"
                     x-text="nombre.charAt(0)"></div>
                <div>
                    <p class="font-bold text-gray-800 text-sm" x-text="nombre"></p>
                    <p class="text-xs text-gray-500">
                        WhatsApp: <span class="font-mono font-semibold" x-text="'+51 ' + numero"></span>
                        <span x-show="saldo > 0" class="ml-2 text-red-500 font-semibold">· Saldo: S/. <span x-text="saldo"></span></span>
                    </p>
                </div>
            </div>

            {{-- Mensaje editable --}}
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                Mensaje
                <span class="normal-case font-normal text-gray-400 ml-1">— puedes editarlo antes de enviar</span>
            </label>
            <textarea x-model="mensaje" rows="6"
                      class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm outline-none resize-none
                             focus:border-[#25D366] focus:ring-2 focus:ring-[#25D366]/20 bg-gray-50 focus:bg-white
                             transition-all leading-relaxed"></textarea>

            {{-- Plantilla rápida --}}
            <div class="mt-3">
                <p class="text-xs text-gray-400 mb-2 font-semibold">Plantillas rápidas:</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="usarPlantilla('bienvenida')"
                            class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-green-100 text-green-700 hover:bg-green-200 transition-colors">
                        🎓 Bienvenida
                    </button>
                    <button type="button" @click="usarPlantilla('recordatorio')"
                            class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                        📋 Recordatorio pago
                    </button>
                    <button type="button" @click="usarPlantilla('renovacion')"
                            class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                        🔄 Renovación
                    </button>
                    <button type="button" @click="usarPlantilla('vencimiento')"
                            class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                        ⏰ Próximo vencimiento
                    </button>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3">
            <button @click="show = false"
                    class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-200 transition-colors">
                Cancelar
            </button>
            <button @click="enviar()"
                    :disabled="!mensaje.trim() || !numero"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white
                           bg-[#25D366] hover:bg-[#1ebe5d] disabled:opacity-50 disabled:cursor-not-allowed
                           shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
                </svg>
                Abrir WhatsApp
            </button>
        </div>
    </div>
</div>

</div>{{-- /x-data waModal --}}

@push('scripts')
<script>
const WA_PLANTILLAS = {
    recordatorio: {!! json_encode(wa_plantilla_recordatorio('{nombre}', '{saldo}', '{dias}'), JSON_UNESCAPED_UNICODE) !!},
    renovacion:   {!! json_encode(wa_plantilla_renovacion('{nombre}', '{saldo}', '{dias}'), JSON_UNESCAPED_UNICODE) !!},
    vencimiento:  {!! json_encode(wa_plantilla_vencimiento('{nombre}', '{saldo}', '{dias}'), JSON_UNESCAPED_UNICODE) !!},
    bienvenida:   {!! json_encode(wa_plantilla_bienvenida('{nombre}', '{email}'), JSON_UNESCAPED_UNICODE) !!},
};

function waReemplazar(tpl, vars) {
    return tpl
        .replace(/\{nombre\}/g, vars.nombre ?? '')
        .replace(/\{saldo\}/g,  vars.saldo  ?? '')
        .replace(/\{dias\}/g,   vars.dias   ?? '')
        .replace(/\{email\}/g,  vars.email  ?? '');
}

function waModal() {
    return {
        show: false,
        nombre: '',
        numero: '',
        saldo: '0.00',
        dias: 0,
        email: '',
        mensaje: '',
        abrir(nombre, numero, saldo, dias, email = '') {
            this.nombre = nombre;
            this.numero = numero;
            this.saldo  = saldo;
            this.dias   = dias;
            this.email  = email;
            this.mensaje = waReemplazar(WA_PLANTILLAS.recordatorio, { nombre, saldo, dias, email });
            this.show = true;
        },
        usarPlantilla(tipo) {
            this.mensaje = waReemplazar(WA_PLANTILLAS[tipo] ?? WA_PLANTILLAS.recordatorio, {
                nombre: this.nombre,
                saldo:  this.saldo,
                dias:   this.dias,
                email:  this.email,
            });
        },
        enviar() {
            let tel = this.numero.replace(/\D/g, '');
            if (tel.length === 9) tel = '51' + tel;
            window.open('https://wa.me/' + tel + '?text=' + encodeURIComponent(this.mensaje), '_blank');
            this.show = false;
        }
    }
}
</script>
@endpush

@endsection
