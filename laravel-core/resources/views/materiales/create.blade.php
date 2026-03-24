@extends('layouts.dashboard')
@section('title', 'Nuevo Material')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('materiales.index') }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('materiales.index') }}" class="hover:text-accent transition-colors">Materiales</a>
                <span class="mx-1">/</span>
                <span class="text-gray-600">Nuevo material</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">Nuevo Material</h1>
        </div>
    </div>

    @include('materiales._flash')

    <form method="POST" action="{{ route('materiales.store') }}">
        @csrf

        @include('materiales._form', ['material' => null])

        <div class="flex items-center justify-end gap-3 mt-5">
            <a href="{{ route('materiales.index') }}"
               class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl font-bold text-sm text-white
                           bg-gradient-to-r from-primary-dark to-primary-light
                           hover:from-accent hover:to-secondary transition-all duration-300 shadow-md hover:shadow-lg">
                Agregar material
            </button>
        </div>
    </form>

</div>
@endsection
