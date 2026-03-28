@extends('layouts.dashboard')
@section('title', $lead->nombreCompleto())

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('leads.index') }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('leads.index') }}" class="hover:text-accent transition-colors">Prospectos</a>
                <span class="mx-1">/</span>
                <span class="text-gray-600">{{ $lead->nombreCompleto() }}</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">Gestionar prospecto</h1>
        </div>
        <form method="POST" action="{{ route('leads.destroy', $lead) }}"
              onsubmit="return confirm('¿Eliminar este prospecto? Esta acción no se puede deshacer.')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold
                           border border-gray-200 text-gray-400 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Eliminar
            </button>
        </form>
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

    {{-- Hero del prospecto --}}
    <div class="relative bg-gradient-to-br from-primary-dark via-[#0e3d7a] to-[#30A9D9] rounded-3xl p-6 mb-5 text-white overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="relative flex items-center gap-4 flex-wrap">
            <div class="w-16 h-16 rounded-2xl bg-white/20 flex items-center justify-center font-black text-2xl flex-shrink-0">
                {{ $lead->inicial() }}
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-xl font-black leading-tight">{{ $lead->nombreCompleto() }}</h2>
                <p class="text-white/60 text-sm mt-0.5">{{ $lead->email }}</p>
                @if($lead->telefono)
                    <p class="text-white/60 text-sm">{{ $lead->telefono }}</p>
                @endif
            </div>
            <div class="flex flex-col items-end gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold
                             {{ $lead->estadoColor() }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $lead->estadoDot() }}"></span>
                    {{ $lead->estadoLabel() }}
                </span>
                <span class="text-white/40 text-xs">Registrado {{ $lead->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>

    {{-- Info del interés --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-2">Nivel de interés</p>
            <p class="text-base font-black text-gray-700">{{ $lead->nivelLabel() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-2">Plan consultado</p>
            <p class="text-sm font-black text-gray-700 truncate">{{ $lead->plan->nombre ?? '—' }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-2">Origen</p>
            <p class="text-sm font-black text-gray-700 capitalize">{{ $lead->origen }}</p>
        </div>
    </div>

    @if($lead->mensaje)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Mensaje del prospecto</p>
        <p class="text-sm text-gray-700 leading-relaxed italic">"{{ $lead->mensaje }}"</p>
    </div>
    @endif

    {{-- Formulario de gestión --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-accent/10 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-gray-700">Gestión del prospecto</h3>
        </div>

        <form method="POST" action="{{ route('leads.update', $lead) }}" class="p-5 space-y-4">
            @csrf @method('PUT')

            {{-- Estado --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">
                    Estado <span class="text-red-400">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2"
                     x-data="{ estado: '{{ old('estado', $lead->estado) }}' }">
                    <input type="hidden" name="estado" :value="estado">
                    @foreach([
                        'nuevo'       => ['🆕', 'Nuevo',       'bg-blue-500',    'border-blue-400 bg-blue-50 text-blue-700'],
                        'contactado'  => ['📞', 'Contactado',  'bg-amber-500',   'border-amber-400 bg-amber-50 text-amber-700'],
                        'matriculado' => ['✅', 'Matriculado', 'bg-emerald-500', 'border-emerald-400 bg-emerald-50 text-emerald-700'],
                        'descartado'  => ['🚫', 'Descartado',  'bg-gray-400',    'border-gray-300 bg-gray-50 text-gray-600'],
                    ] as $val => [$icon, $label, $dot, $active])
                    <div @click="estado = '{{ $val }}'"
                         :class="estado === '{{ $val }}' ? '{{ $active }} ring-2 ring-offset-1 ring-{{ explode(' ', $active)[2] }}/50' : 'border-gray-200 bg-gray-50 hover:border-gray-300'"
                         class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all select-none">
                        <span class="text-base leading-none">{{ $icon }}</span>
                        <span class="text-xs font-bold">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Teléfono --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">
                    Teléfono / WhatsApp
                </label>
                <input type="text" name="telefono" value="{{ old('telefono', $lead->telefono) }}"
                       placeholder="987 654 321"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm outline-none transition-all
                              focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white">
            </div>

            {{-- Plan --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">
                    Plan de interés
                </label>
                <select name="plan_id"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm outline-none transition-all
                               focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white">
                    <option value="">Sin plan asignado</option>
                    @foreach($planes as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id', $lead->plan_id) == $plan->id ? 'selected' : '' }}>
                            {{ $plan->nombre }} — S/. {{ number_format($plan->precio, 0) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Notas admin --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">
                    Notas internas
                    <span class="text-gray-300 font-normal normal-case">(solo visibles para el admin)</span>
                </label>
                <textarea name="notas_admin" rows="4"
                          placeholder="Ej: Llamado el 28/03, interesado en plan mensual, prefiere horario tarde..."
                          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm outline-none transition-all resize-none
                                 focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white">{{ old('notas_admin', $lead->notas_admin) }}</textarea>
            </div>

            {{-- Timeline rápido --}}
            @if($lead->contactado_en)
            <div class="flex items-center gap-2 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-amber-700">
                    Marcado como contactado el <strong>{{ $lead->contactado_en->format('d/m/Y H:i') }}</strong>
                </p>
            </div>
            @endif

            <button type="submit"
                    class="w-full py-3 rounded-xl font-bold text-sm text-white
                           bg-gradient-to-r from-primary-dark to-primary-light
                           hover:from-accent hover:to-secondary transition-all shadow-sm">
                Guardar cambios
            </button>
        </form>
    </div>

</div>

@endsection
