@extends('layouts.dashboard')
@section('title', 'Prospectos')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-dark to-primary-light flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <div>
            <p class="text-xl font-black text-gray-800 leading-none">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
            <span class="text-lg">🆕</span>
        </div>
        <div>
            <p class="text-xl font-black text-blue-700 leading-none">{{ $stats['nuevos'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Nuevos</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
            <span class="text-lg">📞</span>
        </div>
        <div>
            <p class="text-xl font-black text-amber-700 leading-none">{{ $stats['contactados'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Contactados</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <span class="text-lg">✅</span>
        </div>
        <div>
            <p class="text-xl font-black text-emerald-700 leading-none">{{ $stats['matriculados'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Matriculados</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center flex-shrink-0">
            <span class="text-lg">🚫</span>
        </div>
        <div>
            <p class="text-xl font-black text-gray-500 leading-none">{{ $stats['descartados'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Descartados</p>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex items-center justify-between mb-5 gap-4 flex-wrap">
    <div>
        <h1 class="text-2xl font-black text-primary-dark">Prospectos</h1>
        <p class="text-gray-400 text-sm mt-0.5">
            {{ $leads->total() }} {{ $leads->total() === 1 ? 'registro' : 'registros' }} de interesados desde la web
        </p>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-2xl mb-4 text-sm text-emerald-800 font-semibold">
    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Filtros --}}
<form method="GET" action="{{ route('leads.index') }}"
      class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
        <div class="relative sm:col-span-2">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar por nombre, email o teléfono..."
                   class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm
                          focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
        </div>
        <select name="estado"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                       focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
            <option value="">Todos los estados</option>
            <option value="nuevo"       {{ request('estado') === 'nuevo'       ? 'selected' : '' }}>🆕 Nuevo</option>
            <option value="contactado"  {{ request('estado') === 'contactado'  ? 'selected' : '' }}>📞 Contactado</option>
            <option value="matriculado" {{ request('estado') === 'matriculado' ? 'selected' : '' }}>✅ Matriculado</option>
            <option value="descartado"  {{ request('estado') === 'descartado'  ? 'selected' : '' }}>🚫 Descartado</option>
        </select>
        <div class="flex gap-2">
            <select name="nivel"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                           focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none bg-gray-50 focus:bg-white">
                <option value="">Todos los niveles</option>
                <option value="pollito"    {{ request('nivel') === 'pollito'    ? 'selected' : '' }}>🐣 Pollito</option>
                <option value="intermedio" {{ request('nivel') === 'intermedio' ? 'selected' : '' }}>⚡ Intermedio</option>
                <option value="no_sabe"    {{ request('nivel') === 'no_sabe'    ? 'selected' : '' }}>❓ No sabe</option>
            </select>
            <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-primary-dark text-white text-sm font-semibold
                           hover:bg-accent transition-colors">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar','estado','nivel']))
            <a href="{{ route('leads.index') }}"
               class="px-3 py-2.5 rounded-xl border border-gray-200 text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-colors flex-shrink-0">
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

    @if($leads->isEmpty())
    <div class="py-20 text-center">
        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-semibold">No se encontraron prospectos</p>
        <p class="text-gray-400 text-sm mt-1">
            @if(request()->hasAny(['buscar','estado','nivel']))
                <a href="{{ route('leads.index') }}" class="text-accent hover:underline">Limpiar filtros</a>
            @else
                Aparecerán aquí cuando alguien llene el formulario de contacto en la web.
            @endif
        </p>
    </div>
    @else

    {{-- Desktop --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white text-left">
                    <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Prospecto</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Contacto</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Nivel / Plan</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Estado</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider">Fecha</th>
                    <th class="px-5 py-3.5 font-semibold text-gray-400 text-xs uppercase tracking-wider text-right">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                <tr class="border-b border-gray-50 hover:bg-accent/5 transition-colors group">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0 shadow-sm
                                        {{ $lead->estado === 'nuevo' ? 'bg-gradient-to-br from-primary-dark to-primary-light text-white'
                                            : ($lead->estado === 'matriculado' ? 'bg-gradient-to-br from-emerald-500 to-emerald-400 text-white'
                                            : 'bg-gray-100 text-gray-500') }}">
                                {{ $lead->inicial() }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 group-hover:text-primary-dark transition-colors">
                                    {{ $lead->nombreCompleto() }}
                                </p>
                                @if($lead->mensaje)
                                    <p class="text-xs text-gray-400 truncate max-w-[180px]">{{ $lead->mensaje }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-xs text-gray-600">{{ $lead->email }}</p>
                        @if($lead->telefono)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $lead->telefono }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-xs font-semibold text-gray-600">{{ $lead->nivelLabel() }}</p>
                        @if($lead->plan)
                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[120px]">{{ $lead->plan->nombre }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $lead->estadoColor() }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $lead->estadoDot() }}"></span>
                            {{ $lead->estadoLabel() }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-xs text-gray-400">
                        {{ $lead->created_at->format('d/m/Y') }}
                        <br>{{ $lead->created_at->diffForHumans() }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('leads.show', $lead) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold
                                  bg-primary-dark/5 text-primary-dark hover:bg-primary-dark hover:text-white transition-all">
                            Gestionar
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile --}}
    <div class="md:hidden divide-y divide-gray-50">
        @foreach($leads as $lead)
        <div class="p-4">
            <div class="flex items-start justify-between gap-3 mb-2">
                <div>
                    <p class="font-semibold text-gray-800 text-sm">{{ $lead->nombreCompleto() }}</p>
                    <p class="text-xs text-gray-400">{{ $lead->email }}</p>
                    @if($lead->telefono)
                        <p class="text-xs text-gray-400">{{ $lead->telefono }}</p>
                    @endif
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold border {{ $lead->estadoColor() }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $lead->estadoDot() }}"></span>
                    {{ $lead->estadoLabel() }}
                </span>
            </div>
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-400">{{ $lead->nivelLabel() }} · {{ $lead->created_at->format('d/m/Y') }}</p>
                <a href="{{ route('leads.show', $lead) }}"
                   class="text-xs font-bold text-accent hover:underline">Gestionar →</a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Paginación --}}
    @if($leads->hasPages())
    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $leads->links() }}
    </div>
    @endif
    @endif
</div>

@endsection
