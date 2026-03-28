@extends('layouts.dashboard')
@section('title', 'Credenciales de Acceso')

@push('styles')
<style>
    @media print {
        body * { visibility: hidden !important; }
        #zona-impresion, #zona-impresion * { visibility: visible !important; }
        #zona-impresion { position: fixed; inset: 0; padding: 2rem; }
        .no-print { display: none !important; }
    }
</style>
@endpush

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-5 no-print">
        <a href="{{ route('alumnos.show', $alumno) }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-xl font-black text-primary-dark leading-none">Alumno registrado</h1>
            <p class="text-xs text-gray-400 mt-0.5">Paso 1 completado · Guarda las credenciales antes de continuar</p>
        </div>
        <button onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-semibold text-sm text-gray-600
                       border border-gray-200 hover:bg-gray-50 transition-all no-print">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Imprimir
        </button>
    </div>

    {{-- Stepper --}}
    @include('partials.flow-stepper', ['flowStep' => 2, 'flowAlumno' => $alumno])

    {{-- Aviso --}}
    @if(!session('credenciales'))
    <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-2xl mb-3 no-print">
        <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-blue-700">
            La contraseña inicial del alumno es su <strong>DNI</strong>. Si ya la cambió, deberá restablecerla manualmente.
        </p>
    </div>
    @endif
    <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-2xl mb-5 no-print">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <p class="text-sm font-bold text-amber-800">Anota o imprime estas credenciales ahora</p>
            <p class="text-xs text-amber-700 mt-0.5">Las contraseñas solo se muestran una vez en esta pantalla.</p>
        </div>
    </div>

    {{-- Zona imprimible --}}
    <div id="zona-impresion" class="space-y-4 mb-6">

        <div class="hidden print:block text-center mb-6">
            <p class="text-lg font-black text-gray-800">Academia Nueva Era</p>
            <p class="text-sm text-gray-500">Credenciales de acceso al sistema · {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        {{-- Card Alumno --}}
        <div class="bg-white rounded-2xl border-2 border-primary-dark/20 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-3.5 bg-gradient-to-r from-primary-dark to-primary-light">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center font-black text-white text-base flex-shrink-0">
                    {{ strtoupper(substr($credenciales['alumno']['nombre'], 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs text-white/60 uppercase tracking-wide font-semibold">Alumno</p>
                    <p class="font-black text-white leading-tight">{{ $credenciales['alumno']['nombre'] }}</p>
                </div>
                <div class="ml-auto">
                    <span class="text-xs bg-white/20 text-white font-bold px-3 py-1 rounded-full">
                        {{ $alumno->tipo === 'pollito' ? '🐣 Pollito' : '⚡ Intermedio' }}
                    </span>
                </div>
            </div>
            <div class="p-5 space-y-3">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">DNI</p>
                    <p class="font-mono text-sm font-bold text-gray-800 bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100 tracking-widest">
                        {{ $alumno->dni }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Correo</p>
                        <p class="font-mono text-sm text-gray-800 bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100 break-all">
                            {{ $credenciales['alumno']['email'] }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Contraseña temporal</p>
                        <div class="relative">
                            <p class="font-mono text-sm font-black text-primary-dark bg-primary-dark/5 px-4 py-2.5 rounded-xl border border-primary-dark/20 tracking-widest">
                                {{ $credenciales['alumno']['password'] }}
                            </p>
                            <span class="absolute -top-1.5 -right-1.5 text-[9px] font-bold bg-amber-400 text-white px-1.5 py-0.5 rounded-full uppercase">temporal</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Representante --}}
        @if($credenciales['representante'])
        {{-- Tiene representante: mostrar credenciales --}}
        <div class="bg-white rounded-2xl border-2 border-violet-200 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-3.5 bg-gradient-to-r from-violet-600 to-purple-500">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center font-black text-white text-base flex-shrink-0">
                    {{ strtoupper(substr($credenciales['representante']['nombre'], 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs text-white/60 uppercase tracking-wide font-semibold">Representante / Apoderado</p>
                    <p class="font-black text-white leading-tight">{{ $credenciales['representante']['nombre'] }}</p>
                </div>
            </div>
            <div class="p-5 space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Correo</p>
                        <p class="font-mono text-sm text-gray-800 bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100 break-all">
                            {{ $credenciales['representante']['email'] }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Contraseña temporal</p>
                        @if($credenciales['representante']['password'])
                        <div class="relative">
                            <p class="font-mono text-sm font-black text-violet-700 bg-violet-50 px-4 py-2.5 rounded-xl border border-violet-200 tracking-widest">
                                {{ $credenciales['representante']['password'] }}
                            </p>
                            <span class="absolute -top-1.5 -right-1.5 text-[9px] font-bold bg-amber-400 text-white px-1.5 py-0.5 rounded-full uppercase">temporal</span>
                        </div>
                        @else
                        <div class="flex items-center gap-2">
                            <p class="flex-1 font-mono text-xs text-gray-400 bg-gray-50 px-3 py-2.5 rounded-xl border border-gray-200 italic">
                                No recuperable
                            </p>
                            <form method="POST" action="{{ route('alumnos.reset-representante', $alumno) }}" class="no-print flex-shrink-0">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('¿Restablecer la contraseña del representante? Se asignará una nueva contraseña temporal.')"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold
                                               bg-violet-600 text-white hover:bg-violet-700 transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Restablecer
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @elseif(!$alumno->representante_id)
        {{-- Sin representante: formulario para agregar uno --}}
        <div class="bg-white rounded-2xl border-2 border-dashed border-violet-200 shadow-sm overflow-hidden no-print"
             x-data="{ abierto: {{ $errors->hasAny(['nombre_rep','apellidos_rep','email_rep']) ? 'true' : 'false' }} }">

            {{-- Cabecera / toggle --}}
            <button type="button" @click="abierto = !abierto"
                    class="w-full flex items-center justify-between gap-3 px-5 py-4 hover:bg-violet-50/50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                         :class="abierto ? 'bg-violet-100' : 'bg-gray-100'">
                        <svg class="w-4 h-4 transition-colors" :class="abierto ? 'text-violet-600' : 'text-gray-400'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-gray-700">Representante / Apoderado</p>
                        <p class="text-xs text-gray-400" x-text="abierto ? 'Completa los datos para crear su cuenta' : 'Sin representante registrado · clic para agregar'"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full transition-colors"
                          :class="abierto ? 'bg-violet-100 text-violet-700' : 'bg-gray-100 text-gray-500'"
                          x-text="abierto ? 'Cancelar' : '+ Agregar'"></span>
                </div>
            </button>

            {{-- Formulario expandible --}}
            <div x-show="abierto" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="border-t border-violet-100">
                <form method="POST" action="{{ route('alumnos.store-representante', $alumno) }}" class="p-5">
                    @csrf

                    @if($errors->hasAny(['nombre_rep','apellidos_rep','email_rep']))
                    <div class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl mb-4">
                        <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <ul class="text-xs text-red-700 space-y-0.5">
                            @foreach(['nombre_rep','apellidos_rep','email_rep'] as $campo)
                                @error($campo)<li>{{ $message }}</li>@enderror
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="flex items-start gap-2 p-3 bg-violet-50 border border-violet-100 rounded-xl mb-4">
                        <svg class="w-4 h-4 text-violet-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-violet-700">Se creará una cuenta de acceso. La contraseña temporal será <strong>Rep{{ $alumno->dni }}</strong>.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                                Nombres <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre_rep"
                                   value="{{ old('nombre_rep') }}"
                                   placeholder="María"
                                   class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all bg-gray-50 focus:bg-white focus:border-violet-400 focus:ring-2 focus:ring-violet-100
                                          {{ $errors->has('nombre_rep') ? 'border-red-400' : 'border-gray-200' }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                                Apellidos <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="apellidos_rep"
                                   value="{{ old('apellidos_rep') }}"
                                   placeholder="García López"
                                   class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all bg-gray-50 focus:bg-white focus:border-violet-400 focus:ring-2 focus:ring-violet-100
                                          {{ $errors->has('apellidos_rep') ? 'border-red-400' : 'border-gray-200' }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                                Correo electrónico <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <input type="email" name="email_rep"
                                       value="{{ old('email_rep') }}"
                                       placeholder="representante@ejemplo.com"
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm outline-none transition-all bg-gray-50 focus:bg-white focus:border-violet-400 focus:ring-2 focus:ring-violet-100
                                              {{ $errors->has('email_rep') ? 'border-red-400' : 'border-gray-200' }}">
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 rounded-xl font-bold text-sm text-white
                                   bg-gradient-to-r from-violet-600 to-purple-500
                                   hover:from-violet-700 hover:to-purple-600 transition-all shadow-sm">
                        Registrar representante y generar credenciales
                    </button>
                </form>
            </div>
        </div>
        @endif

        <div class="hidden print:block text-center mt-8 pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-400">Academia Nueva Era · Sistema de Gestión Académica</p>
        </div>
    </div>

    {{-- CTA principal: continuar al siguiente paso --}}
    <div class="no-print">
        <a href="{{ route('matriculas.create', ['alumno_id' => $alumno->id, 'flow' => 1]) }}"
           class="group flex items-center justify-between w-full px-6 py-4 rounded-2xl font-bold text-white
                  bg-gradient-to-r from-primary-dark to-primary-light shadow-lg
                  hover:from-accent hover:to-secondary hover:shadow-xl hover:-translate-y-0.5
                  transition-all duration-300 mb-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="text-left">
                    <p class="text-base font-black leading-tight">Continuar → Matricular alumno</p>
                    <p class="text-xs text-white/70 font-normal mt-0.5">Paso 2 de 3 · Elige el plan y las fechas</p>
                </div>
            </div>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <div class="flex items-center gap-3">
            <button onclick="window.print()"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm
                           border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Imprimir credenciales
            </button>
            <a href="{{ route('alumnos.show', $alumno) }}"
               class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm
                      border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
                Ver perfil del alumno
            </a>
            <a href="{{ route('alumnos.create') }}"
               class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm
                      border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
                Registrar otro alumno
            </a>
        </div>
    </div>

</div>

@endsection
