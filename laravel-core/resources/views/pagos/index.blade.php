@extends('layouts.dashboard')
@section('title', 'Pagos')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Pagos</h1>
        <p class="text-gray-500 text-sm mt-0.5">{{ $pagos->total() }} registrados</p>
    </div>
    <a href="{{ route('pagos.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
              bg-gradient-to-r from-primary-dark to-primary-light
              hover:from-accent hover:to-secondary transition-all duration-300 shadow-md">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Pago
    </a>
</div>

@include('pagos._flash')

{{-- Filtros --}}
<form method="GET" action="{{ route('pagos.index') }}"
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

        <select name="estado"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                       focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-white">
            <option value="">Todos los estados</option>
            <option value="confirmado" {{ request('estado') === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
            <option value="pendiente"  {{ request('estado') === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
            <option value="anulado"    {{ request('estado') === 'anulado'    ? 'selected' : '' }}>Anulado</option>
        </select>

        <div class="flex gap-2">
            <select name="metodo_pago"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                           focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-white">
                <option value="">Todos los métodos</option>
                @foreach(['efectivo' => 'Efectivo', 'transferencia' => 'Transferencia', 'yape' => 'Yape', 'plin' => 'Plin', 'tarjeta' => 'Tarjeta'] as $val => $label)
                    <option value="{{ $val }}" {{ request('metodo_pago') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-primary-dark text-white text-sm font-semibold
                           hover:bg-accent transition-colors">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar','estado','metodo_pago']))
                <a href="{{ route('pagos.index') }}"
                   class="px-3 py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors"
                   title="Limpiar filtros">
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

    @if($pagos->isEmpty())
        <div class="py-16 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-gray-400 font-medium">No se encontraron pagos</p>
            @if(request()->hasAny(['buscar','estado','metodo_pago']))
                <a href="{{ route('pagos.index') }}" class="text-accent text-sm mt-1 inline-block hover:underline">Limpiar filtros</a>
            @else
                <a href="{{ route('pagos.create') }}" class="text-accent text-sm mt-1 inline-block hover:underline">Registrar el primer pago</a>
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
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Monto</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Método</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Fecha</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Registrado por</th>
                        <th class="px-5 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($pagos as $pago)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-4 text-gray-400 text-xs font-mono">{{ $pago->id }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-primary-dark/10 flex items-center justify-center
                                            text-xs font-bold text-primary-dark flex-shrink-0">
                                    {{ $pago->matricula->alumno->inicial() }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 leading-none">
                                        {{ $pago->matricula->alumno->nombreCompleto() }}
                                    </p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $pago->matricula->alumno->dni }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-gray-700">{{ $pago->matricula->plan->nombre }}</td>
                        <td class="px-5 py-4 font-black text-gray-800 font-mono">S/. {{ number_format($pago->monto, 2) }}</td>
                        <td class="px-5 py-4">
                            @php
                                $metodoBadge = match($pago->metodo_pago) {
                                    'yape'          => 'bg-purple-100 text-purple-700',
                                    'plin'          => 'bg-blue-100 text-blue-700',
                                    'transferencia' => 'bg-indigo-100 text-indigo-700',
                                    'tarjeta'       => 'bg-gray-100 text-gray-700',
                                    default         => 'bg-green-100 text-green-700',
                                };
                            @endphp
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full capitalize {{ $metodoBadge }}">
                                {{ $pago->metodo_pago }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-gray-600">{{ $pago->fecha_pago?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-5 py-4">
                            @include('pagos._badge', ['estado' => $pago->estado])
                        </td>
                        <td class="px-5 py-4 text-gray-500 text-xs">{{ $pago->user?->name ?? '—' }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('pagos.show', $pago) }}"
                                   class="p-1.5 rounded-lg text-gray-400 hover:text-accent hover:bg-accent/10 transition-all"
                                   title="Ver">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if($pago->estado !== 'anulado')
                                    <a href="{{ route('pagos.edit', $pago) }}"
                                       class="p-1.5 rounded-lg text-gray-400 hover:text-primary-light hover:bg-primary-light/10 transition-all"
                                       title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('pagos.destroy', $pago) }}"
                                          onsubmit="return confirm('¿Anular este pago?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all"
                                                title="Anular">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden divide-y divide-gray-100">
            @foreach($pagos as $pago)
            <div class="p-4">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $pago->matricula->alumno->nombreCompleto() }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $pago->matricula->alumno->dni }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $pago->matricula->plan->nombre }}</p>
                    </div>
                    @include('pagos._badge', ['estado' => $pago->estado])
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                    <span class="font-black text-gray-800">S/. {{ number_format($pago->monto, 2) }}</span>
                    <span class="capitalize">{{ $pago->metodo_pago }}</span>
                    <span>{{ $pago->fecha_pago?->format('d/m/Y') }}</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('pagos.show', $pago) }}"
                       class="flex-1 text-center py-1.5 rounded-lg text-xs font-semibold
                              bg-accent/10 text-accent hover:bg-accent hover:text-white transition-all">Ver</a>
                    @if($pago->estado !== 'anulado')
                        <a href="{{ route('pagos.edit', $pago) }}"
                           class="flex-1 text-center py-1.5 rounded-lg text-xs font-semibold
                                  bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">Editar</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($pagos->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $pagos->links() }}
        </div>
        @endif
    @endif
</div>

@endsection
