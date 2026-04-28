@extends('layouts.dashboard')
@section('title', 'Nuevo Anuncio')

@section('content')

<div class="max-w-2xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('anuncios.index') }}"
           class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center
                  text-gray-400 hover:text-primary-dark hover:border-gray-300 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-primary-dark">Nuevo anuncio</h1>
            <p class="text-gray-400 text-sm">Crea una publicación para alumnos y representantes</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('anuncios.store') }}" enctype="multipart/form-data"
              class="space-y-5">
            @csrf
            @include('anuncios._form')

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('anuncios.index') }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-100 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold text-white
                               bg-gradient-to-r from-primary-dark to-primary-light
                               hover:from-accent hover:to-secondary transition-all duration-300 shadow-md">
                    Publicar anuncio
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
