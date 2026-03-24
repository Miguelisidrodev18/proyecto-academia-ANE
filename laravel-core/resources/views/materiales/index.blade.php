@extends('layouts.dashboard')
@section('title', 'Materiales')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total materiales</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['visibles'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Visibles</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['ocultos'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Ocultos</p>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex items-center justify-between mb-5 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Materiales</h1>
        <p class="text-gray-400 text-sm mt-0.5">{{ $materiales->total() }} {{ $materiales->total() === 1 ? 'material registrado' : 'materiales registrados' }}</p>
    </div>
    <a href="{{ route('materiales.create', request()->only('curso_id')) }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
              bg-gradient-to-r from-primary-dark to-primary-light
              hover:from-accent hover:to-secondary transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Material
    </a>
</div>

@include('materiales._flash')

{{-- Filtros --}}
<form method="GET" action="{{ route('materiales.index') }}"
      class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
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

        <select name="tipo"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                       focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
            <option value="">Todos los tipos</option>
            <option value="pdf"    {{ request('tipo') === 'pdf'    ? 'selected' : '' }}>PDF</option>
            <option value="video"  {{ request('tipo') === 'video'  ? 'selected' : '' }}>Video</option>
            <option value="enlace" {{ request('tipo') === 'enlace' ? 'selected' : '' }}>Enlace</option>
            <option value="imagen" {{ request('tipo') === 'imagen' ? 'selected' : '' }}>Imagen</option>
            <option value="otro"   {{ request('tipo') === 'otro'   ? 'selected' : '' }}>Otro</option>
        </select>

        <select name="visible"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                       focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
            <option value="">Visible e Invisible</option>
            <option value="1" {{ request('visible') === '1' ? 'selected' : '' }}>Solo visibles</option>
            <option value="0" {{ request('visible') === '0' ? 'selected' : '' }}>Solo ocultos</option>
        </select>

        <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-primary-dark text-white text-sm font-semibold
                           hover:bg-accent transition-colors duration-200">
                Filtrar
            </button>
            @if(request()->hasAny(['curso_id','tipo','visible']))
                <a href="{{ route('materiales.index') }}"
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

    @if($materiales->isEmpty())
        <div class="py-20 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-gray-500 font-semibold text-base">No se encontraron materiales</p>
            <p class="text-gray-400 text-sm mt-1">
                @if(request()->hasAny(['curso_id','tipo','visible']))
                    Intenta con otros filtros o
                    <a href="{{ route('materiales.index') }}" class="text-accent font-semibold hover:underline">limpia la búsqueda</a>
                @else
                    <a href="{{ route('materiales.create') }}" class="text-accent font-semibold hover:underline">Agrega el primer material</a>
                @endif
            </p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left bg-gradient-to-r from-gray-50 to-white">
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Tipo</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Material</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Curso</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Publicado</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Visible</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materiales as $material)
                    @php
                        $tipoConfig = match($material->tipo) {
                            'pdf'    => ['icon' => '📄', 'color' => 'bg-red-100 text-red-700'],
                            'video'  => ['icon' => '🎥', 'color' => 'bg-violet-100 text-violet-700'],
                            'enlace' => ['icon' => '🔗', 'color' => 'bg-blue-100 text-blue-700'],
                            'imagen' => ['icon' => '🖼️', 'color' => 'bg-amber-100 text-amber-700'],
                            default  => ['icon' => '📎', 'color' => 'bg-gray-100 text-gray-600'],
                        };
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gradient-to-r hover:from-accent/5 hover:to-transparent transition-all duration-150 group">
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $tipoConfig['color'] }}">
                                {{ $tipoConfig['icon'] }} {{ ucfirst($material->tipo) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-800 group-hover:text-primary-dark transition-colors">{{ $material->titulo }}</p>
                            @if($material->descripcion)
                                <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $material->descripcion }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                         bg-primary-dark/10 text-primary-dark">
                                {{ $material->curso->nombre }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-xs text-gray-500">
                            {{ $material->fecha_publicacion->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-4">
                            <form method="POST" action="{{ route('materiales.toggle', $material) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold transition-all
                                               {{ $material->visible
                                                   ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100'
                                                   : 'bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200' }}"
                                        title="{{ $material->visible ? 'Ocultar' : 'Mostrar' }}">
                                    @if($material->visible)
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Visible
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Oculto
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ $material->url }}" target="_blank"
                                   class="p-2 rounded-lg text-gray-400 hover:text-blue-500 hover:bg-blue-50 transition-all duration-150" title="Abrir enlace">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                                <a href="{{ route('materiales.edit', $material) }}"
                                   class="p-2 rounded-lg text-gray-400 hover:text-primary-light hover:bg-primary-light/10 transition-all duration-150" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('materiales.destroy', $material) }}"
                                      onsubmit="return confirm('¿Eliminar el material {{ addslashes($material->titulo) }}?')">
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

        @if($materiales->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $materiales->links() }}
        </div>
        @endif
    @endif
</div>

@endsection
