@extends('layouts.dashboard')
@section('title', 'Editar Clase')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('clases.show', $clase) }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('clases.index') }}" class="hover:text-accent transition-colors">Clases</a>
                <span class="mx-1">/</span>
                <a href="{{ route('clases.show', $clase) }}" class="hover:text-accent transition-colors">{{ $clase->titulo }}</a>
                <span class="mx-1">/</span>
                <span class="text-gray-600">Editar</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">Editar Clase</h1>
        </div>
    </div>

    @include('clases._flash')

    <form method="POST" action="{{ route('clases.update', $clase) }}">
        @csrf @method('PATCH')

        @include('clases._form', ['cursoSeleccionado' => null])

        <div class="flex items-center justify-end gap-3 mt-5">
            <a href="{{ route('clases.show', $clase) }}"
               class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl font-bold text-sm text-white
                           bg-gradient-to-r from-primary-dark to-primary-light
                           hover:from-accent hover:to-secondary transition-all duration-300 shadow-md hover:shadow-lg">
                Guardar cambios
            </button>
        </div>
    </form>

</div>
@endsection
