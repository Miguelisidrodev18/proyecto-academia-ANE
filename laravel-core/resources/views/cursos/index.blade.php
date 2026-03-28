@extends('layouts.dashboard')
@section('title', 'Cursos')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total cursos</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['activos'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Cursos activos</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['pollito'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Nivel Pollito</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats['intermedio'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Nivel Intermedio</p>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Cursos</h1>
        <p class="text-gray-400 text-sm mt-0.5">{{ $cursos->count() }} {{ $cursos->count() === 1 ? 'curso registrado' : 'cursos registrados' }}, organizados por plan</p>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('cursos.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
              bg-gradient-to-r from-primary-dark to-primary-light
              hover:from-accent hover:to-secondary transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Curso
    </a>
    @endif
</div>

@include('cursos._flash')

@if($cursos->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-24 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-semibold text-base">No hay cursos registrados</p>
        @if(auth()->user()->isAdmin())
        <p class="text-gray-400 text-sm mt-1">
            <a href="{{ route('cursos.create') }}" class="text-accent font-semibold hover:underline">Crear el primer curso</a>
        </p>
        @endif
    </div>
@else

    {{-- ── Secciones por plan ───────────────────────────────────────────── --}}
    @foreach($planes as $plan)
        @php $listaCursos = $cursosPorPlan[$plan->id] ?? collect(); @endphp
        @if($listaCursos->isEmpty()) @continue @endif

        <div class="mb-10">
            {{-- Encabezado de sección --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-2.5 px-4 py-2 rounded-xl
                            bg-gradient-to-r from-primary-dark/90 to-primary-light/80 shadow-sm">
                    <span class="text-base leading-none">{{ $plan->tipoIcono() }}</span>
                    <span class="text-white font-black text-sm">{{ $plan->nombre }}</span>
                    <span class="text-white/60 text-xs font-medium">· {{ $listaCursos->count() }} {{ $listaCursos->count() === 1 ? 'curso' : 'cursos' }}</span>
                </div>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($listaCursos as $curso)
                    @include('cursos._card', ['curso' => $curso])
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- Cursos sin plan asignado --}}
    @if($sinPlan->isNotEmpty())
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-2.5 px-4 py-2 rounded-xl bg-gray-200 shadow-sm">
                    <span class="text-gray-500 font-black text-sm">Sin plan asignado</span>
                    <span class="text-gray-400 text-xs font-medium">· {{ $sinPlan->count() }} {{ $sinPlan->count() === 1 ? 'curso' : 'cursos' }}</span>
                </div>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($sinPlan as $curso)
                    @include('cursos._card', ['curso' => $curso])
                @endforeach
            </div>
        </div>
    @endif

@endif

@endsection
