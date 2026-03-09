@extends('layouts.dashboard')
@section('title', 'Documentos — ' . $alumno->nombreCompleto())

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('alumnos.show', $alumno) }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-gray-300 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-primary-dark">Documentos</h1>
            <p class="text-gray-400 text-sm">{{ $alumno->nombreCompleto() }}</p>
        </div>
        @include('alumnos._badge', ['tipo' => $alumno->tipo])
    </div>

    @include('alumnos._flash')

    {{-- Subir documento --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">
        <h2 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Subir nuevo documento
        </h2>
        <form method="POST"
              action="{{ route('alumnos.documentos.subir', $alumno) }}"
              enctype="multipart/form-data"
              class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @csrf

            <div class="sm:col-span-1">
                <label class="block text-sm font-semibold text-gray-600 mb-1">
                    Nombre del documento <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                       placeholder="DNI, Constancia, etc."
                       @class([
                           'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all',
                           'focus:border-accent focus:ring-2 focus:ring-accent/20',
                           'border-red-400 bg-red-50' => $errors->has('nombre'),
                           'border-gray-200'           => !$errors->has('nombre'),
                       ])>
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-1">
                <label class="block text-sm font-semibold text-gray-600 mb-1">
                    Archivo <span class="text-red-500">*</span>
                    <span class="text-gray-400 font-normal">(PDF, JPG, PNG — máx. 2MB)</span>
                </label>
                <input type="file" name="documento" accept=".pdf,.jpg,.jpeg,.png"
                       @class([
                           'w-full px-3 py-2 rounded-xl border text-sm outline-none transition-all file:mr-3',
                           'file:px-3 file:py-1 file:rounded-lg file:border-0',
                           'file:bg-accent/10 file:text-accent file:text-xs file:font-semibold',
                           'hover:file:bg-accent hover:file:text-white',
                           'border-red-400 bg-red-50' => $errors->has('documento'),
                           'border-gray-200'           => !$errors->has('documento'),
                       ])>
                @error('documento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="w-full px-4 py-2.5 rounded-xl font-bold text-sm text-white
                               bg-gradient-to-r from-primary-dark to-primary-light
                               hover:from-accent hover:to-secondary transition-all duration-300 shadow-sm">
                    Subir documento
                </button>
            </div>
        </form>
    </div>

    {{-- Lista de documentos --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-700">
                Archivos subidos
                <span class="ml-1 text-sm font-normal text-gray-400">
                    ({{ count($alumno->documentos ?? []) }})
                </span>
            </h2>
        </div>

        @if(empty($alumno->documentos))
            <div class="py-12 text-center">
                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-gray-400 font-medium text-sm">Aún no hay documentos subidos</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($alumno->documentos as $indice => $doc)
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/50 transition-colors">

                    {{-- Icono --}}
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                {{ in_array($doc['extension'], ['jpg','jpeg','png'])
                                   ? 'bg-blue-50 text-blue-500'
                                   : 'bg-red-50 text-red-500' }}">
                        @if(in_array($doc['extension'], ['jpg','jpeg','png']))
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-700 text-sm truncate">{{ $doc['nombre'] }}</p>
                        <p class="text-xs text-gray-400">
                            {{ strtoupper($doc['extension']) }} &middot; Subido el {{ $doc['fecha'] }}
                        </p>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ Storage::disk('public')->url($doc['ruta']) }}"
                           target="_blank"
                           class="p-2 rounded-lg text-gray-400 hover:text-accent hover:bg-accent/10 transition-all"
                           title="Ver/Descargar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                        <form method="POST"
                              action="{{ route('alumnos.documentos.eliminar', [$alumno, $indice]) }}"
                              onsubmit="return confirm('¿Eliminar el documento «{{ $doc['nombre'] }}»?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all"
                                    title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    </div>

</div>

@endsection
