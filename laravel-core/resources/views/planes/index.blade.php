@extends('layouts.dashboard')
@section('title', 'Planes')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total planes</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['activos'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Planes activos</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-accent to-secondary flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['alumnos_activos'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Alumnos inscritos</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">S/. {{ number_format($stats['ingreso_potencial'], 0) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Precio total activos</p>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex items-center justify-between mb-5 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Planes</h1>
        <p class="text-gray-400 text-sm mt-0.5">
            {{ $planes->count() }} {{ $planes->count() === 1 ? 'plan registrado' : 'planes registrados' }}
        </p>
    </div>
    <a href="{{ route('planes.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
              bg-gradient-to-r from-primary-dark to-primary-light
              hover:from-accent hover:to-secondary transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Plan
    </a>
</div>

@include('planes._flash')

{{-- Grid de cards --}}
@if($planes->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-24 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-semibold text-base">No hay planes registrados</p>
        <p class="text-gray-400 text-sm mt-1">
            <a href="{{ route('planes.create') }}" class="text-accent font-semibold hover:underline">Crear el primer plan</a>
        </p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($planes as $plan)
            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                        hover:shadow-lg hover:-translate-y-1 transition-all duration-300
                        {{ !$plan->activo ? 'opacity-60' : '' }}">

                {{-- Header de la card --}}
                <div class="px-5 py-5 relative"
                     @if($plan->esVip())
                         style="background: linear-gradient(135deg, #1a0a00 0%, #3d1f00 50%, #6b3500 100%);"
                     @else
                         style="background: linear-gradient(to right, #082B59, #30A9D9);"
                     @endif>
                    @if($plan->esVip())
                        <div class="absolute inset-0 pointer-events-none"
                             style="background: radial-gradient(ellipse at 90% 0%, rgba(251,191,36,0.2) 0%, transparent 60%);"></div>
                    @endif

                    {{-- Badges top-right --}}
                    <div class="absolute top-3 right-3 flex flex-col items-end gap-1">
                        @if($plan->esVip())
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                                         bg-gradient-to-r from-amber-400 to-yellow-300 text-amber-900 text-[10px] font-black uppercase tracking-wide shadow-sm">
                                💎 VIP
                            </span>
                        @endif
                        @if($plan->acceso_ilimitado)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                                         bg-amber-400/20 text-amber-300 text-[10px] font-bold border border-amber-400/30 uppercase tracking-wide">
                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Ilimitado
                            </span>
                        @endif
                        @if($plan->mostrar_en_landing)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                                         bg-accent/30 text-white text-[10px] font-bold border border-accent/40 uppercase tracking-wide">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                En landing
                            </span>
                        @endif
                    </div>

                    <p class="{{ $plan->esVip() ? 'text-amber-300/60' : 'text-white/60' }} text-xs font-semibold uppercase tracking-widest mb-1">Plan</p>
                    <h2 class="text-xl font-black {{ $plan->esVip() ? 'text-amber-200' : 'text-white' }} leading-tight">{{ $plan->nombre }}</h2>

                    {{-- Precio --}}
                    <div class="flex items-end gap-1 mt-3">
                        <span class="text-3xl font-black {{ $plan->esVip() ? 'text-amber-100' : 'text-white' }}">S/. {{ number_format($plan->precio, 0) }}</span>
                        <span class="{{ $plan->esVip() ? 'text-amber-300/50' : 'text-white/50' }} text-xs mb-1">
                            /
                            @if($plan->acceso_ilimitado)
                                acceso ilimitado
                            @else
                                {{ $plan->duracion_meses }} {{ $plan->duracion_meses == 1 ? 'mes' : 'meses' }}
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Cuerpo de la card --}}
                <div class="px-5 py-4 flex-1">

                    {{-- Descripción --}}
                    @if($plan->descripcion)
                        <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-2">{{ $plan->descripcion }}</p>
                    @else
                        <p class="text-gray-300 text-sm italic mb-4">Sin descripción.</p>
                    @endif

                    {{-- Métricas --}}
                    <div class="flex items-center gap-4 py-3 border-t border-gray-100">
                        {{-- Alumnos activos --}}
                        <div class="flex items-center gap-1.5">
                            <div class="w-7 h-7 rounded-lg bg-accent/10 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-base font-black text-gray-800 leading-none">{{ $plan->alumnos_activos_count }}</p>
                                <p class="text-[10px] text-gray-400 leading-none mt-0.5">alumnos</p>
                            </div>
                        </div>

                        {{-- Estado --}}
                        <div class="ml-auto">
                            <span @class([
                                'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold',
                                'bg-emerald-50 text-emerald-700 border border-emerald-200' => $plan->activo,
                                'bg-gray-100 text-gray-500 border border-gray-200'         => !$plan->activo,
                            ])>
                                <span class="w-1.5 h-1.5 rounded-full {{ $plan->activo ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                {{ $plan->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Footer de la card --}}
                <div class="px-5 pb-4 pt-0 flex items-center gap-2">
                    <a href="{{ route('planes.show', $plan) }}"
                       class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-primary-dark/5 text-primary-dark
                              hover:bg-primary-dark hover:text-white transition-all duration-200">
                        Ver detalle
                    </a>
                    <a href="{{ route('planes.edit', $plan) }}"
                       class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-gray-100 text-gray-600
                              hover:bg-gray-200 transition-all duration-200">
                        Editar
                    </a>
                    {{-- Toggle landing --}}
                    <form method="POST" action="{{ route('planes.toggle-landing', $plan) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                title="{{ $plan->mostrar_en_landing ? 'Quitar de landing page' : 'Mostrar en landing page' }}"
                                class="p-2 rounded-xl transition-all duration-200
                                       {{ $plan->mostrar_en_landing
                                            ? 'bg-accent/15 text-accent hover:bg-accent/25'
                                            : 'bg-gray-100 text-gray-400 hover:bg-accent/10 hover:text-accent' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </button>
                    </form>
                    {{-- Toggle activo --}}
                    <form method="POST" action="{{ route('planes.toggle', $plan) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                title="{{ $plan->activo ? 'Desactivar' : 'Activar' }}"
                                class="p-2 rounded-xl transition-all duration-200
                                       {{ $plan->activo
                                            ? 'bg-amber-50 text-amber-600 hover:bg-amber-100'
                                            : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}">
                            @if($plan->activo)
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            @else
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
