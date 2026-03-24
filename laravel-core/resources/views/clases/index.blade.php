@extends('layouts.dashboard')
@section('title', 'Clases')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total clases</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['proximas'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Próximas</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['pasadas'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Realizadas</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['con_grabacion'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Con grabación</p>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex items-center justify-between mb-5 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Clases</h1>
        <p class="text-gray-400 text-sm mt-0.5">{{ $clases->total() }} {{ $clases->total() === 1 ? 'clase registrada' : 'clases registradas' }}</p>
    </div>
    <a href="{{ route('clases.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
              bg-gradient-to-r from-primary-dark to-primary-light
              hover:from-accent hover:to-secondary transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Clase
    </a>
</div>

@include('clases._flash')

{{-- Filtros --}}
<form method="GET" action="{{ route('clases.index') }}"
      class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <select name="curso_id"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                       focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
            <option value="">Todos los cursos</option>
            @foreach($cursos as $curso)
                <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                    {{ $curso->nombre }}
                </option>
            @endforeach
        </select>

        <select name="estado"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                       focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
            <option value="">Todas</option>
            <option value="proxima" {{ request('estado') === 'proxima' ? 'selected' : '' }}>Próximas</option>
            <option value="pasada"  {{ request('estado') === 'pasada'  ? 'selected' : '' }}>Realizadas</option>
        </select>

        <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-primary-dark text-white text-sm font-semibold
                           hover:bg-accent transition-colors duration-200">
                Filtrar
            </button>
            @if(request()->hasAny(['curso_id','estado']))
                <a href="{{ route('clases.index') }}"
                   class="px-3 py-2.5 rounded-xl border border-gray-200 text-gray-400 hover:bg-red-50 hover:text-red-500
                          hover:border-red-200 transition-colors duration-200 flex-shrink-0" title="Limpiar filtros">
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

    @if($clases->isEmpty())
        <div class="py-20 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-gray-500 font-semibold text-base">No se encontraron clases</p>
            <p class="text-gray-400 text-sm mt-1">
                @if(request()->hasAny(['curso_id','estado']))
                    Intenta con otros filtros o
                    <a href="{{ route('clases.index') }}" class="text-accent font-semibold hover:underline">limpia la búsqueda</a>
                @else
                    <a href="{{ route('clases.create') }}" class="text-accent font-semibold hover:underline">Crea la primera clase</a>
                @endif
            </p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left bg-gradient-to-r from-gray-50 to-white">
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Fecha</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Clase</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Curso</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Zoom / Grab.</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clases as $clase)
                    @php $esPasada = $clase->fecha->isPast(); @endphp
                    <tr class="border-b border-gray-50 hover:bg-gradient-to-r hover:from-accent/5 hover:to-transparent transition-all duration-150 group">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <p class="font-semibold text-gray-800 text-xs">{{ $clase->fecha->format('d/m/Y') }}</p>
                            <p class="text-gray-400 text-xs">{{ $clase->fecha->format('H:i') }}</p>
                            @if(!$esPasada)
                                <span class="inline-block mt-1 text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Próxima</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-800 group-hover:text-primary-dark transition-colors">{{ $clase->titulo }}</p>
                            @if($clase->descripcion)
                                <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $clase->descripcion }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                         bg-primary-dark/10 text-primary-dark">
                                {{ $clase->curso->nombre }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                @if($clase->zoom_link)
                                    <a href="{{ $clase->zoom_link }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-lg
                                              bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Zoom
                                    </a>
                                @endif
                                @if($clase->grabacion_url)
                                    <a href="{{ $clase->grabacion_url }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-lg
                                              bg-violet-50 text-violet-600 hover:bg-violet-100 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Grab.
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('clases.show', $clase) }}"
                                   class="p-2 rounded-lg text-gray-400 hover:text-accent hover:bg-accent/10 transition-all duration-150" title="Ver detalle">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('asistencias.registrar', $clase) }}"
                                   class="p-2 rounded-lg text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 transition-all duration-150" title="Registrar asistencia">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </a>
                                <a href="{{ route('clases.edit', $clase) }}"
                                   class="p-2 rounded-lg text-gray-400 hover:text-primary-light hover:bg-primary-light/10 transition-all duration-150" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('clases.destroy', $clase) }}"
                                      onsubmit="return confirm('¿Eliminar la clase {{ addslashes($clase->titulo) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all duration-150" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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

        @if($clases->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $clases->links() }}
        </div>
        @endif
    @endif
</div>

@endsection
