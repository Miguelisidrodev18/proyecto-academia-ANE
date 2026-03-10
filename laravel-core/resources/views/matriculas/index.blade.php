@extends('layouts.dashboard')
@section('title', 'Matrículas')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Matrículas</h1>
        <p class="text-gray-500 text-sm mt-0.5">{{ $matriculas->total() }} registradas</p>
    </div>
    <a href="{{ route('matriculas.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
              bg-gradient-to-r from-primary-dark to-primary-light
              hover:from-accent hover:to-secondary transition-all duration-300 shadow-md">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Matrícula
    </a>
</div>

@include('matriculas._flash')

{{-- Filtros --}}
<form method="GET" action="{{ route('matriculas.index') }}"
      class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">

        <div class="relative sm:col-span-2">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar por nombre o DNI del alumno..."
                   class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm
                          focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition-all">
        </div>

        <select name="plan_id"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                       focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-white">
            <option value="">Todos los planes</option>
            @foreach($planes as $plan)
                <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                    {{ $plan->nombre }}
                </option>
            @endforeach
        </select>

        <div class="flex gap-2">
            <select name="estado"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                           focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-white">
                <option value="">Todos los estados</option>
                <option value="activa"     {{ request('estado') === 'activa'     ? 'selected' : '' }}>Activa</option>
                <option value="vencida"    {{ request('estado') === 'vencida'    ? 'selected' : '' }}>Vencida</option>
                <option value="suspendida" {{ request('estado') === 'suspendida' ? 'selected' : '' }}>Suspendida</option>
                <option value="pendiente"  {{ request('estado') === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
            </select>
            <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-primary-dark text-white text-sm font-semibold
                           hover:bg-accent transition-colors">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar','plan_id','estado']))
                <a href="{{ route('matriculas.index') }}"
                   class="px-3 py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors"
                   title="Limpiar">
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

    @if($matriculas->isEmpty())
        <div class="py-16 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="text-gray-400 font-medium">No se encontraron matrículas</p>
            @if(request()->hasAny(['buscar','plan_id','estado']))
                <a href="{{ route('matriculas.index') }}" class="text-accent text-sm mt-1 inline-block hover:underline">Limpiar filtros</a>
            @else
                <a href="{{ route('matriculas.create') }}" class="text-accent text-sm mt-1 inline-block hover:underline">Registrar la primera matrícula</a>
            @endif
        </div>
    @else
        {{-- Desktop --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-left">
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">#</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Alumno</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Plan</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Precio</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">T. Pago</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Inicio</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Fin</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Días rest.</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($matriculas as $matricula)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-4 text-gray-400 text-xs font-mono">{{ $matricula->id }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-primary-dark/10 flex items-center justify-center
                                            text-xs font-bold text-primary-dark flex-shrink-0">
                                    {{ $matricula->alumno->inicial() }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 leading-none">{{ $matricula->alumno->nombreCompleto() }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $matricula->alumno->dni }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-gray-700 font-medium">{{ $matricula->plan->nombre }}</td>
                        <td class="px-5 py-4 text-gray-700 font-mono">S/. {{ number_format($matricula->precio_pagado, 2) }}</td>
                        <td class="px-5 py-4">
                            @php
                                $tipoBadge = match($matricula->tipo_pago) {
                                    'mensual' => 'bg-blue-100 text-blue-700',
                                    'cuotas'  => 'bg-purple-100 text-purple-700',
                                    default   => 'bg-green-100 text-green-700',
                                };
                                $tipoLabel = match($matricula->tipo_pago) {
                                    'mensual' => 'Mensual',
                                    'cuotas'  => 'Cuotas',
                                    default   => 'Completo',
                                };
                            @endphp
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $tipoBadge }}">{{ $tipoLabel }}</span>
                        </td>
                        <td class="px-5 py-4 text-gray-600">{{ $matricula->fecha_inicio?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-5 py-4 text-gray-600">{{ $matricula->fecha_fin?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-5 py-4">
                            @php $dias = $matricula->diasRestantes(); @endphp
                            <span class="font-semibold {{ $dias <= 7 ? 'text-red-500' : ($dias <= 30 ? 'text-yellow-600' : 'text-gray-700') }}">
                                {{ $dias }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @include('matriculas._badge', ['estado' => $matricula->estado])
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('matriculas.show', $matricula) }}"
                                   class="p-1.5 rounded-lg text-gray-400 hover:text-accent hover:bg-accent/10
                                          transition-all" title="Ver">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('matriculas.edit', $matricula) }}"
                                   class="p-1.5 rounded-lg text-gray-400 hover:text-primary-light hover:bg-primary-light/10
                                          transition-all" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('matriculas.destroy', $matricula) }}"
                                      onsubmit="return confirm('¿Eliminar esta matrícula?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50
                                                   transition-all" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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

        {{-- Mobile cards --}}
        <div class="md:hidden divide-y divide-gray-100">
            @foreach($matriculas as $matricula)
            <div class="p-4">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $matricula->alumno->nombreCompleto() }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $matricula->alumno->dni }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $matricula->plan->nombre }}</p>
                    </div>
                    @include('matriculas._badge', ['estado' => $matricula->estado])
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-500 mb-3">
                    <span>S/. {{ number_format($matricula->precio_pagado, 2) }}</span>
                    <span>{{ $matricula->fecha_inicio?->format('d/m/Y') }} → {{ $matricula->fecha_fin?->format('d/m/Y') }}</span>
                    <span class="font-semibold {{ $matricula->diasRestantes() <= 7 ? 'text-red-500' : 'text-gray-600' }}">
                        {{ $matricula->diasRestantes() }} días rest.
                    </span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('matriculas.show', $matricula) }}"
                       class="flex-1 text-center py-1.5 rounded-lg text-xs font-semibold
                              bg-accent/10 text-accent hover:bg-accent hover:text-white transition-all">Ver</a>
                    <a href="{{ route('matriculas.edit', $matricula) }}"
                       class="flex-1 text-center py-1.5 rounded-lg text-xs font-semibold
                              bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">Editar</a>
                </div>
            </div>
            @endforeach
        </div>

        @if($matriculas->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $matriculas->links() }}
        </div>
        @endif
    @endif
</div>

@endsection
