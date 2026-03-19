@extends('layouts.dashboard')
@section('title', 'Panel Representante')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-black text-primary-dark">
        ¡Bienvenido, {{ explode(' ', auth()->user()->name)[0] }}!
    </h1>
    <p class="text-gray-500 text-sm mt-1">
        Panel del representante — Academia Nueva Era
    </p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-16 text-center">
    <div class="w-16 h-16 bg-gradient-to-br from-primary-dark to-primary-light rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </div>
    <p class="text-gray-700 font-bold text-lg">Panel de Representante</p>
    <p class="text-gray-400 text-sm mt-2 max-w-xs mx-auto">
        Aquí podrás ver el progreso y estado académico del alumno a tu cargo. Próximamente disponible.
    </p>
</div>

@endsection
