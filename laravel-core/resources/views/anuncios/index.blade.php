@extends('layouts.dashboard')
@section('title', 'Anuncios')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total anuncios</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['activos'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Activos</p>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex items-center justify-between mb-5 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Anuncios</h1>
        <p class="text-gray-400 text-sm mt-0.5">Publicaciones visibles para alumnos y representantes</p>
    </div>
    <a href="{{ route('anuncios.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
              bg-gradient-to-r from-primary-dark to-primary-light
              hover:from-accent hover:to-secondary transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo anuncio
    </a>
</div>

@include('anuncios._flash')

@if($anuncios->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-24 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-semibold text-base">No hay anuncios creados</p>
        <p class="text-gray-400 text-sm mt-1">
            <a href="{{ route('anuncios.create') }}" class="text-accent font-semibold hover:underline">Crear el primer anuncio</a>
        </p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($anuncios as $anuncio)
            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                        hover:shadow-lg hover:-translate-y-1 transition-all duration-300
                        {{ !$anuncio->activo ? 'opacity-60' : '' }}">

                {{-- Imagen --}}
                @if($anuncio->imagenUrl())
                    <div class="relative h-44 overflow-hidden bg-gray-100">
                        <img src="{{ $anuncio->imagenUrl() }}" alt="{{ $anuncio->titulo }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        {{-- Badge estado --}}
                        <div class="absolute top-2 right-2">
                            <span @class([
                                'inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold backdrop-blur-sm',
                                'bg-emerald-500/90 text-white' => $anuncio->activo,
                                'bg-gray-500/80 text-white'   => !$anuncio->activo,
                            ])>
                                <span class="w-1.5 h-1.5 rounded-full {{ $anuncio->activo ? 'bg-white' : 'bg-gray-300' }}"></span>
                                {{ $anuncio->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="h-28 flex items-center justify-center
                                bg-gradient-to-br from-primary-dark/5 to-primary-light/10 border-b border-gray-100">
                        <svg class="w-12 h-12 text-primary-dark/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </div>
                @endif

                {{-- Body --}}
                <div class="p-4">
                    @if($anuncio->titulo)
                        <h3 class="font-black text-gray-800 text-sm leading-tight mb-1 line-clamp-2">{{ $anuncio->titulo }}</h3>
                    @endif

                    @if($anuncio->descripcion)
                        <p class="text-gray-500 text-xs leading-relaxed line-clamp-2">{{ $anuncio->descripcion }}</p>
                    @endif

                    {{-- Badges destinatarios + link --}}
                    <div class="flex flex-wrap items-center gap-1.5 mt-3">
                        @foreach($anuncio->destinatarios ?? [] as $dest)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary-dark/8 text-primary-dark capitalize">
                                {{ $dest }}
                            </span>
                        @endforeach

                        @if($anuncio->link_url)
                            <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-bold
                                         {{ $anuncio->tipo_link === 'whatsapp' ? 'bg-green-100 text-green-700' : 'bg-accent/10 text-accent' }}">
                                {{ $anuncio->tipo_link === 'whatsapp' ? 'WhatsApp' : 'Enlace externo' }}
                            </span>
                        @endif
                    </div>

                    {{-- Planes --}}
                    @if(!empty($anuncio->planes_ids))
                    @php
                        $planesAnuncio = \App\Models\Plan::whereIn('id', $anuncio->planes_ids)->pluck('nombre');
                    @endphp
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($planesAnuncio as $nombrePlan)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-accent/10 text-accent">
                                {{ $nombrePlan }}
                            </span>
                        @endforeach
                    </div>
                    @else
                    <p class="text-[10px] text-gray-400 mt-1.5">Todos los planes</p>
                    @endif

                    {{-- Fechas --}}
                    @if($anuncio->fecha_inicio || $anuncio->fecha_fin)
                        <p class="text-[10px] text-gray-400 mt-2">
                            @if($anuncio->fecha_inicio)
                                Desde {{ $anuncio->fecha_inicio->format('d/m/Y') }}
                            @endif
                            @if($anuncio->fecha_fin)
                                hasta {{ $anuncio->fecha_fin->format('d/m/Y') }}
                            @endif
                        </p>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="px-4 pb-4 flex items-center gap-2">
                    <a href="{{ route('anuncios.edit', $anuncio) }}"
                       class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-gray-100 text-gray-600
                              hover:bg-gray-200 transition-all duration-200">
                        Editar
                    </a>

                    {{-- Toggle activo --}}
                    <form method="POST" action="{{ route('anuncios.toggle', $anuncio) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                title="{{ $anuncio->activo ? 'Desactivar' : 'Activar' }}"
                                class="p-2 rounded-xl transition-all duration-200
                                       {{ $anuncio->activo
                                            ? 'bg-amber-50 text-amber-600 hover:bg-amber-100'
                                            : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}">
                            @if($anuncio->activo)
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

                    {{-- Eliminar --}}
                    <form method="POST" action="{{ route('anuncios.destroy', $anuncio) }}"
                          onsubmit="return confirm('¿Eliminar este anuncio?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                title="Eliminar"
                                class="p-2 rounded-xl bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 transition-all duration-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
