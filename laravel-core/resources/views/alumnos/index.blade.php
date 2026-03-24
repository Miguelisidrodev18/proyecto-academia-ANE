@extends('layouts.dashboard')
@section('title', 'Alumnos')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 group hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats->total ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total alumnos</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats->activos ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Activos</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-sky-400 to-cyan-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats->pollitos ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Pollitos</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats->intermedios ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Intermedios</p>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex items-center justify-between mb-5 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Alumnos</h1>
        <p class="text-gray-400 text-sm mt-0.5">
            {{ $alumnos->total() }} {{ $alumnos->total() === 1 ? 'alumno registrado' : 'alumnos registrados' }}
        </p>
    </div>
    <a href="{{ route('alumnos.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
              bg-gradient-to-r from-primary-dark to-primary-light
              hover:from-accent hover:to-secondary transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Alumno
    </a>
</div>

@include('alumnos._flash')

{{-- Filtros --}}
<form method="GET" action="{{ route('alumnos.index') }}"
      class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

        {{-- Búsqueda con placeholder dinámico --}}
        <div class="relative sm:col-span-1"
             x-data="{
                 ph: 'Buscar por nombre...',
                 idx: 0,
                 opts: ['Buscar por nombre...', 'Buscar por DNI...', 'Buscar por email...'],
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

        {{-- Filtro tipo --}}
        <select name="tipo"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                       focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
            <option value="">Todos los niveles</option>
            <option value="pollito"   {{ request('tipo') === 'pollito'   ? 'selected' : '' }}>🐣 Pollito</option>
            <option value="intermedio" {{ request('tipo') === 'intermedio' ? 'selected' : '' }}>⚡ Intermedio</option>
        </select>

        {{-- Filtro estado + botones --}}
        <div class="flex gap-2">
            <select name="estado"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                           focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
                <option value="">Todos los estados</option>
                <option value="activo"   {{ request('estado') === 'activo'   ? 'selected' : '' }}>✅ Activos</option>
                <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>⭕ Inactivos</option>
            </select>
            <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-primary-dark text-white text-sm font-semibold
                           hover:bg-accent transition-colors duration-200 flex-shrink-0">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar','tipo','estado']))
                <a href="{{ route('alumnos.index') }}"
                   class="px-3 py-2.5 rounded-xl border border-gray-200 text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200
                          transition-colors duration-200 flex-shrink-0" title="Limpiar filtros">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            @endif
        </div>
    </div>
</form>

{{-- Tabla / Cards --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    @if($alumnos->isEmpty())
        <div class="py-20 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-gray-500 font-semibold text-base">No se encontraron alumnos</p>
            <p class="text-gray-400 text-sm mt-1">
                @if(request()->hasAny(['buscar','tipo','estado']))
                    Intenta con otros filtros o
                    <a href="{{ route('alumnos.index') }}" class="text-accent font-semibold hover:underline">limpia la búsqueda</a>
                @else
                    <a href="{{ route('alumnos.create') }}" class="text-accent font-semibold hover:underline">Registra el primer alumno</a>
                @endif
            </p>
        </div>
    @else
        {{-- Desktop table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left bg-gradient-to-r from-gray-50 to-white">
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">DNI</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Alumno</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Contacto</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Nivel</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alumnos as $alumno)
                    <tr class="border-b border-gray-50 hover:bg-gradient-to-r hover:from-accent/5 hover:to-transparent transition-all duration-150 group">
                        <td class="px-5 py-4 font-mono text-gray-500 text-xs font-medium tracking-wide">{{ $alumno->dni }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 font-black text-sm shadow-sm
                                            {{ $alumno->esIntermedio() ? 'bg-gradient-to-br from-violet-400 to-purple-500 text-white' : 'bg-gradient-to-br from-sky-400 to-cyan-500 text-white' }}">
                                    {{ $alumno->inicial() }}
                                </div>
                                <span class="font-semibold text-gray-800 group-hover:text-primary-dark transition-colors">{{ $alumno->nombreCompleto() }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-gray-600 text-xs">{{ $alumno->user->email ?? '—' }}</p>
                            @if($alumno->telefono)
                                <p class="text-gray-400 text-xs mt-0.5">{{ $alumno->telefono }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @include('alumnos._badge', ['tipo' => $alumno->tipo])
                        </td>
                        <td class="px-5 py-4">
                            @if($alumno->estado)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-500 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('alumnos.show', $alumno) }}"
                                   class="p-2 rounded-lg text-gray-400 hover:text-accent hover:bg-accent/10 transition-all duration-150" title="Ver perfil">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('alumnos.edit', $alumno) }}"
                                   class="p-2 rounded-lg text-gray-400 hover:text-primary-light hover:bg-primary-light/10 transition-all duration-150" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('alumnos.cambiarEstado', $alumno) }}"
                                      title="{{ $alumno->estado ? 'Desactivar alumno' : 'Activar alumno' }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="p-2 rounded-lg transition-all duration-150
                                                   {{ $alumno->estado
                                                       ? 'text-gray-400 hover:text-amber-500 hover:bg-amber-50'
                                                       : 'text-gray-400 hover:text-emerald-500 hover:bg-emerald-50' }}">
                                        @if($alumno->estado)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
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
            @foreach($alumnos as $alumno)
            <div class="p-4 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center font-black text-base shadow-sm
                                    {{ $alumno->esIntermedio() ? 'bg-gradient-to-br from-violet-400 to-purple-500 text-white' : 'bg-gradient-to-br from-sky-400 to-cyan-500 text-white' }}">
                            {{ $alumno->inicial() }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $alumno->nombreCompleto() }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $alumno->dni }}</p>
                        </div>
                    </div>
                    @include('alumnos._badge', ['tipo' => $alumno->tipo])
                </div>
                <div class="flex gap-2 text-xs text-gray-500 mb-3">
                    @if($alumno->estado)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Activo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactivo
                        </span>
                    @endif
                    @if($alumno->user?->email)
                        <span class="truncate max-w-[160px]">{{ $alumno->user->email }}</span>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('alumnos.show', $alumno) }}"
                       class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-accent/10 text-accent hover:bg-accent hover:text-white transition-all duration-200">
                        Ver perfil
                    </a>
                    <a href="{{ route('alumnos.edit', $alumno) }}"
                       class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all duration-200">
                        Editar
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        @if($alumnos->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $alumnos->links() }}
        </div>
        @endif
    @endif
</div>

@endsection
