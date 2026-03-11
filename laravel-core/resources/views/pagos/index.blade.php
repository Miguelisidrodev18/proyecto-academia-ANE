@extends('layouts.dashboard')
@section('title', 'Pagos')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats->total ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total pagos</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-lg font-black text-gray-800 leading-none">S/. {{ number_format($stats->monto_total ?? 0, 0) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Monto confirmado</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats->pendientes ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Pendientes</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-400 to-rose-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-800 leading-none">{{ $stats->anulados ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Anulados</p>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex items-center justify-between mb-5 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Pagos</h1>
        <p class="text-gray-400 text-sm mt-0.5">
            {{ $pagos->total() }} {{ $pagos->total() === 1 ? 'pago registrado' : 'pagos registrados' }}
        </p>
    </div>
    <a href="{{ route('pagos.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white
              bg-gradient-to-r from-primary-dark to-primary-light
              hover:from-accent hover:to-secondary transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
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

        <div class="relative sm:col-span-2"
             x-data="{
                 ph: 'Buscar por nombre del alumno...',
                 idx: 0,
                 opts: ['Buscar por nombre del alumno...', 'Buscar por DNI...', 'Buscar por referencia...'],
                 init() { setInterval(() => { this.idx = (this.idx + 1) % this.opts.length; this.ph = this.opts[this.idx]; }, 2500); }
             }">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   :placeholder="ph"
                   class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm
                          focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition-all bg-gray-50 focus:bg-white">
        </div>

        <select name="estado"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                       focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
            <option value="">Todos los estados</option>
            <option value="confirmado" {{ request('estado') === 'confirmado' ? 'selected' : '' }}>✅ Confirmado</option>
            <option value="pendiente"  {{ request('estado') === 'pendiente'  ? 'selected' : '' }}>🟡 Pendiente</option>
            <option value="anulado"    {{ request('estado') === 'anulado'    ? 'selected' : '' }}>🔴 Anulado</option>
        </select>

        <div class="flex gap-2">
            <select name="metodo_pago"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                           focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
                <option value="">Todos los métodos</option>
                @foreach(['efectivo' => '💵 Efectivo', 'transferencia' => '🏦 Transferencia', 'yape' => '📱 Yape', 'plin' => '📲 Plin', 'tarjeta' => '💳 Tarjeta', 'mixto' => '🔄 Mixto'] as $val => $label)
                    <option value="{{ $val }}" {{ request('metodo_pago') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-primary-dark text-white text-sm font-semibold
                           hover:bg-accent transition-colors flex-shrink-0">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar','estado','metodo_pago']))
                <a href="{{ route('pagos.index') }}"
                   class="px-3 py-2.5 rounded-xl border border-gray-200 text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-colors flex-shrink-0"
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
        <div class="py-20 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-gray-500 font-semibold text-base">No se encontraron pagos</p>
            <p class="text-gray-400 text-sm mt-1">
                @if(request()->hasAny(['buscar','estado','metodo_pago']))
                    <a href="{{ route('pagos.index') }}" class="text-accent font-semibold hover:underline">Limpiar filtros</a>
                @else
                    <a href="{{ route('pagos.create') }}" class="text-accent font-semibold hover:underline">Registrar el primer pago</a>
                @endif
            </p>
        </div>
    @else

        {{-- Desktop --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left bg-gradient-to-r from-gray-50 to-white">
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">#</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Alumno</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Plan</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Monto</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Método</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Fecha</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Registrado por</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $pago)
                    <tr class="border-b border-gray-50 hover:bg-gradient-to-r hover:from-accent/5 hover:to-transparent transition-all duration-150 group">
                        <td class="px-5 py-4 text-gray-400 text-xs font-mono">{{ $pago->id }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center
                                            text-xs font-black text-white flex-shrink-0 shadow-sm">
                                    {{ $pago->matricula->alumno->inicial() }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 leading-none group-hover:text-primary-dark transition-colors">
                                        {{ $pago->matricula->alumno->nombreCompleto() }}
                                    </p>
                                    <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $pago->matricula->alumno->dni }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-gray-700 font-medium text-xs">{{ $pago->matricula->plan->nombre }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-black text-gray-800 font-mono text-base">S/. {{ number_format($pago->monto, 2) }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @php
                                [$metodoIcon, $metodoBadge] = match($pago->metodo_pago) {
                                    'yape'          => ['📱', 'bg-purple-50 text-purple-700 border border-purple-200'],
                                    'plin'          => ['📲', 'bg-blue-50 text-blue-700 border border-blue-200'],
                                    'transferencia' => ['🏦', 'bg-indigo-50 text-indigo-700 border border-indigo-200'],
                                    'tarjeta'       => ['💳', 'bg-slate-50 text-slate-700 border border-slate-200'],
                                    default         => ['💵', 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full {{ $metodoBadge }}">
                                {{ $metodoIcon }} {{ ucfirst($pago->metodo_pago) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-gray-700 font-medium text-xs">{{ $pago->fecha_pago?->format('d/m/Y') ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            @include('pagos._badge', ['estado' => $pago->estado])
                        </td>
                        <td class="px-5 py-4 text-gray-400 text-xs">{{ $pago->user?->name ?? '—' }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('pagos.show', $pago) }}"
                                   class="p-2 rounded-lg text-gray-400 hover:text-accent hover:bg-accent/10 transition-all"
                                   title="Ver">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if($pago->estado !== 'anulado')
                                    <a href="{{ route('pagos.edit', $pago) }}"
                                       class="p-2 rounded-lg text-gray-400 hover:text-primary-light hover:bg-primary-light/10 transition-all"
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
                                                class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all"
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
        <div class="md:hidden divide-y divide-gray-50">
            @foreach($pagos as $pago)
            <div class="p-4 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $pago->matricula->alumno->nombreCompleto() }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $pago->matricula->alumno->dni }}</p>
                        <p class="text-xs text-primary-dark font-semibold mt-0.5">{{ $pago->matricula->plan->nombre }}</p>
                    </div>
                    @include('pagos._badge', ['estado' => $pago->estado])
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                    <span class="font-black text-gray-800 text-base font-mono">S/. {{ number_format($pago->monto, 2) }}</span>
                    <span class="capitalize">{{ $pago->metodo_pago }}</span>
                    <span>{{ $pago->fecha_pago?->format('d/m/Y') }}</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('pagos.show', $pago) }}"
                       class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-accent/10 text-accent hover:bg-accent hover:text-white transition-all">Ver</a>
                    @if($pago->estado !== 'anulado')
                        <a href="{{ route('pagos.edit', $pago) }}"
                           class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">Editar</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($pagos->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $pagos->links() }}
        </div>
        @endif
    @endif
</div>

@endsection
