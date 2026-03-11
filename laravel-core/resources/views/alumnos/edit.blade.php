@extends('layouts.dashboard')
@section('title', 'Editar Alumno')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('alumnos.show', $alumno) }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('alumnos.index') }}" class="hover:text-accent transition-colors">Alumnos</a>
                <span class="mx-1">/</span>
                <a href="{{ route('alumnos.show', $alumno) }}" class="hover:text-accent transition-colors">{{ $alumno->nombreCompleto() }}</a>
                <span class="mx-1">/</span><span class="text-gray-600">Editar</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">Editar Alumno</h1>
        </div>
        <div class="flex items-center gap-2">
            @include('alumnos._badge', ['tipo' => $alumno->tipo])
            @if($alumno->estado)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Activo
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-500 border border-gray-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactivo
                </span>
            @endif
        </div>
    </div>

    @include('alumnos._flash')

    <form method="POST" action="{{ route('alumnos.update', $alumno) }}">
        @csrf
        @method('PUT')
        @include('alumnos._form')

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
            <a href="{{ route('alumnos.show', $alumno) }}"
               class="px-6 py-3 rounded-xl font-semibold text-sm text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection
