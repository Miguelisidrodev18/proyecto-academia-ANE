@extends('layouts.dashboard')
@section('title', 'Alumnos')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Alumnos</h1>
        <p class="text-gray-500 text-sm mt-0.5">
            {{ $alumnos->total() }} registrados
        </p>
    </div>
    <a href="{{ route('alumnos.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
              bg-gradient-to-r from-primary-dark to-primary-light
              hover:from-accent hover:to-secondary transition-all duration-300 shadow-md">
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

        {{-- Búsqueda --}}
        <div class="relative sm:col-span-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar por nombre, DNI o email..."
                   class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm
                          focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition-all">
        </div>

        {{-- Filtro tipo --}}
        <select name="tipo"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                       focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-white">
            <option value="">Todos los niveles</option>
            <option value="pollito"   {{ request('tipo') === 'pollito'   ? 'selected' : '' }}>Pollito</option>
            <option value="intermedio" {{ request('tipo') === 'intermedio' ? 'selected' : '' }}>Intermedio</option>
        </select>

        {{-- Filtro estado + botón --}}
        <div class="flex gap-2">
            <select name="estado"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                           focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-white">
                <option value="">Todos los estados</option>
                <option value="activo"   {{ request('estado') === 'activo'   ? 'selected' : '' }}>Activos</option>
                <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
            </select>
            <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-primary-dark text-white text-sm font-semibold
                           hover:bg-accent transition-colors duration-200">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar','tipo','estado']))
                <a href="{{ route('alumnos.index') }}"
                   class="px-3 py-2.5 rounded-xl border border-gray-200 text-gray-500 text-sm
                          hover:bg-gray-50 transition-colors duration-200" title="Limpiar filtros">
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

    @if($alumnos->isEmpty())
        <div class="py-16 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-gray-400 font-medium">No se encontraron alumnos</p>
            @if(request()->hasAny(['buscar','tipo','estado']))
                <a href="{{ route('alumnos.index') }}" class="text-accent text-sm mt-1 inline-block hover:underline">
                    Limpiar filtros
                </a>
            @else
                <a href="{{ route('alumnos.create') }}" class="text-accent text-sm mt-1 inline-block hover:underline">
                    Registrar el primer alumno
                </a>
            @endif
        </div>
    @else
        {{-- Desktop table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-left">
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">DNI</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Alumno</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Email</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Teléfono</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Nivel</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($alumnos as $alumno)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-4 font-mono text-gray-700 font-medium">{{ $alumno->dni }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-xs
                                            {{ $alumno->esIntermedio() ? 'bg-purple-100 text-purple-700' : 'bg-sky-100 text-sky-700' }}">
                                    {{ $alumno->inicial() }}
                                </div>
                                <span class="font-semibold text-gray-800">{{ $alumno->nombreCompleto() }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-gray-600">{{ $alumno->user->email ?? '—' }}</td>
                        <td class="px-5 py-4 text-gray-600">{{ $alumno->telefono ?? '—' }}</td>
                        <td class="px-5 py-4">
                            @include('alumnos._badge', ['tipo' => $alumno->tipo])
                        </td>
                        <td class="px-5 py-4">
                            @if($alumno->estado)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold
                                             bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold
                                             bg-gray-100 text-gray-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('alumnos.show', $alumno) }}"
                                   class="p-1.5 rounded-lg text-gray-400 hover:text-accent hover:bg-accent/10
                                          transition-all duration-150" title="Ver">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('alumnos.edit', $alumno) }}"
                                   class="p-1.5 rounded-lg text-gray-400 hover:text-primary-light hover:bg-primary-light/10
                                          transition-all duration-150" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('alumnos.destroy', $alumno) }}"
                                      onsubmit="return confirm('¿Eliminar a {{ $alumno->nombreCompleto() }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50
                                                   transition-all duration-150" title="Eliminar">
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
        <div class="md:hidden divide-y divide-gray-100">
            @foreach($alumnos as $alumno)
            <div class="p-4">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold
                                    {{ $alumno->esIntermedio() ? 'bg-purple-100 text-purple-700' : 'bg-sky-100 text-sky-700' }}">
                            {{ $alumno->inicial() }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $alumno->nombreCompleto() }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $alumno->dni }}</p>
                        </div>
                    </div>
                    @include('alumnos._badge', ['tipo' => $alumno->tipo])
                </div>
                <p class="text-xs text-gray-500 mb-1">{{ $alumno->user->email ?? '—' }}</p>
                @if($alumno->telefono)
                    <p class="text-xs text-gray-500 mb-3">{{ $alumno->telefono }}</p>
                @endif
                <div class="flex gap-2">
                    <a href="{{ route('alumnos.show', $alumno) }}"
                       class="flex-1 text-center py-1.5 rounded-lg text-xs font-semibold
                              bg-accent/10 text-accent hover:bg-accent hover:text-white transition-all">
                        Ver
                    </a>
                    <a href="{{ route('alumnos.edit', $alumno) }}"
                       class="flex-1 text-center py-1.5 rounded-lg text-xs font-semibold
                              bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">
                        Editar
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        @if($alumnos->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $alumnos->links() }}
        </div>
        @endif
    @endif
</div>

@endsection
