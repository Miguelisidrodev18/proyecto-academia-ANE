@extends('layouts.dashboard')
@section('title', 'Editar Curso')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('cursos.show', $curso) }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('cursos.index') }}" class="hover:text-accent transition-colors">Cursos</a>
                <span class="mx-1">/</span>
                <a href="{{ route('cursos.show', $curso) }}" class="hover:text-accent transition-colors">{{ $curso->nombre }}</a>
                <span class="mx-1">/</span><span class="text-gray-600">Editar</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">Editar Curso</h1>
        </div>
    </div>

    @include('cursos._flash')

    <form method="POST" action="{{ route('cursos.update', $curso) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('cursos._form')

        <div class="flex items-center gap-3 mt-5">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-7 py-3 rounded-xl font-bold text-sm text-white
                           bg-gradient-to-r from-primary-dark to-primary-light
                           hover:from-accent hover:to-secondary hover:shadow-lg hover:-translate-y-0.5
                           transition-all duration-300 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar cambios
            </button>
            <a href="{{ route('cursos.show', $curso) }}"
               class="px-6 py-3 rounded-xl font-semibold text-sm text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection
