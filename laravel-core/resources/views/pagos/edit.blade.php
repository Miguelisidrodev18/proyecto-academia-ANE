@extends('layouts.dashboard')
@section('title', 'Editar Pago #' . $pago->id)

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('pagos.show', $pago) }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-gray-300 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-primary-dark">Editar Pago #{{ $pago->id }}</h1>
            <p class="text-gray-400 text-sm">
                {{ $pago->matricula->alumno->nombreCompleto() }} — {{ $pago->matricula->plan->nombre }}
            </p>
        </div>
        <div class="ml-auto">
            @include('pagos._badge', ['estado' => $pago->estado])
        </div>
    </div>

    @include('pagos._flash')

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('pagos.update', $pago) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('pagos._form')

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl font-bold text-sm text-white
                               bg-gradient-to-r from-primary-dark to-primary-light
                               hover:from-accent hover:to-secondary
                               transition-all duration-300 shadow-md">
                    Guardar cambios
                </button>
                <a href="{{ route('pagos.show', $pago) }}"
                   class="px-6 py-2.5 rounded-xl font-semibold text-sm text-gray-600
                          border border-gray-200 hover:bg-gray-50 transition-all">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

</div>

@endsection
