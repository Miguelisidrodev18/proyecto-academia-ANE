@extends('layouts.dashboard')
@section('title', 'Configuración')

@section('content')

@php
$waIconPath = 'M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z';
@endphp

<div class="max-w-3xl" x-data="{ tab: 'numero' }">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-primary-dark">Configuración</h1>
        <p class="text-gray-400 text-sm mt-0.5">Gestiona los mensajes y parámetros del sistema</p>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700 text-sm font-semibold">
        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-1 bg-gray-100 p-1 rounded-2xl mb-6 w-fit">
        <button type="button" @click="tab = 'numero'"
                :class="tab === 'numero'
                    ? 'bg-white text-primary-dark shadow-sm'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                <path d="{{ $waIconPath }}"/>
            </svg>
            Número de contacto
        </button>
        <button type="button" @click="tab = 'admin'"
                :class="tab === 'admin'
                    ? 'bg-white text-primary-dark shadow-sm'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Mensajes a alumnos
        </button>
        <button type="button" @click="tab = 'alumno'"
                :class="tab === 'alumno'
                    ? 'bg-white text-primary-dark shadow-sm'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Mensajes de alumnos
        </button>
        <button type="button" @click="tab = 'bienvenida'"
                :class="tab === 'bienvenida'
                    ? 'bg-white text-primary-dark shadow-sm'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
            </svg>
            Bienvenida
        </button>
    </div>

    <form method="POST" action="{{ route('dashboard.configuracion.guardar') }}">
        @csrf

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- TAB 1: NÚMERO DE CONTACTO                                  --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'numero'" x-cloak>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-5">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-[#25D366]/5 to-white flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#25D366]/15 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-[#25D366]">
                            <path d="{{ $waIconPath }}"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-800">WhatsApp de la academia</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Número al que se dirigen todos los mensajes — tanto los que tú envías a alumnos como los que ellos te envían a ti</p>
                    </div>
                </div>

                <div class="p-5 space-y-5"
                     x-data="{ numero: '{{ old('whatsapp_number', $config['whatsapp_number']) }}' }">

                    {{-- Número --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                            Número de WhatsApp
                        </label>

                        {{-- Input + acciones --}}
                        <div class="flex items-center gap-2 max-w-sm">
                            <div class="relative flex-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-500 select-none pointer-events-none">+51</span>
                                <input type="text" name="whatsapp_number"
                                       x-model="numero"
                                       maxlength="9"
                                       placeholder="987654321"
                                       class="w-full pl-12 pr-10 py-3 rounded-xl border border-gray-200 text-sm outline-none
                                              focus:border-[#25D366] focus:ring-2 focus:ring-[#25D366]/20 bg-gray-50 focus:bg-white
                                              transition-all font-mono tracking-widest
                                              {{ $errors->has('whatsapp_number') ? 'border-red-400 bg-red-50' : '' }}">
                                {{-- Botón limpiar dentro del input --}}
                                <button type="button" x-show="numero" x-cloak
                                        @click="numero = ''"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-gray-300 hover:bg-red-400
                                               flex items-center justify-center transition-colors"
                                        title="Borrar número">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            {{-- Probar --}}
                            <a x-show="numero.length === 9"
                               :href="'https://wa.me/51' + numero + '?text=' + encodeURIComponent('Hola, esta es una prueba de Academia Nueva Era')"
                               target="_blank" rel="noopener"
                               class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-3 rounded-xl text-xs font-bold
                                      bg-[#25D366] text-white hover:bg-[#1ebe5d] transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                    <path d="{{ $waIconPath }}"/>
                                </svg>
                                Probar
                            </a>
                        </div>

                        <p class="text-gray-400 text-xs mt-1.5">Solo los 9 dígitos — el sistema agrega el +51 automáticamente</p>
                        @error('whatsapp_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        {{-- Estado activo --}}
                        <div x-show="numero.length === 9"
                             class="mt-4 p-3 bg-[#ECF8F2] border border-[#25D366]/30 rounded-xl flex items-center justify-between gap-2.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[#25D366] flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-white">
                                        <path d="{{ $waIconPath }}"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-[#075E54]">Número activo</p>
                                    <p class="text-sm font-mono font-bold text-[#128C7E]">+51 <span x-text="numero"></span></p>
                                </div>
                            </div>
                            <button type="button" @click="numero = ''"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold
                                           text-red-500 hover:bg-red-50 border border-red-200 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Desactivar
                            </button>
                        </div>

                        {{-- Sin número --}}
                        <div x-show="!numero"
                             class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-2 text-amber-700 text-xs font-semibold">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Sin número configurado — los botones de WhatsApp no funcionarán hasta que ingreses uno.
                        </div>

                        {{-- Número incompleto --}}
                        <div x-show="numero && numero.length !== 9"
                             class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-xl flex items-center gap-2 text-blue-600 text-xs font-semibold">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Ingresa los 9 dígitos completos (<span x-text="numero.length"></span>/9)
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- TAB 2: MENSAJES ADMIN → ALUMNO                             --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'admin'" x-cloak>

            {{-- Info variables --}}
            <div class="mb-4 px-4 py-3 bg-blue-50 border border-blue-100 rounded-2xl flex items-start gap-2.5">
                <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-blue-700">
                    Estos son los mensajes que <strong>tú envías a los alumnos</strong> desde la tabla de matrículas.
                    Puedes usar:
                    <code class="bg-blue-100 px-1 py-0.5 rounded font-mono">{nombre}</code>
                    <code class="bg-blue-100 px-1 py-0.5 rounded font-mono">{saldo}</code>
                    <code class="bg-blue-100 px-1 py-0.5 rounded font-mono">{dias}</code>
                </p>
            </div>

            @php
            $plantillasAdmin = [
                ['key' => 'wa_msg_recordatorio', 'emoji' => '📋', 'titulo' => 'Recordatorio de pago',
                 'desc' => 'Se usa como mensaje por defecto al abrir el modal de WhatsApp en matrículas'],
                ['key' => 'wa_msg_renovacion',   'emoji' => '🔄', 'titulo' => 'Renovación de membresía',
                 'desc' => 'Cuando la membresía del alumno ya venció y quieres invitarlo a renovar'],
                ['key' => 'wa_msg_vencimiento',  'emoji' => '⏰', 'titulo' => 'Próximo vencimiento',
                 'desc' => 'Recordatorio preventivo cuando el alumno está próximo a vencer'],
            ];
            @endphp

            <div class="space-y-4">
                @foreach($plantillasAdmin as $p)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-3">
                        <span class="text-xl">{{ $p['emoji'] }}</span>
                        <div>
                            <h4 class="text-sm font-black text-gray-800">{{ $p['titulo'] }}</h4>
                            <p class="text-xs text-gray-400">{{ $p['desc'] }}</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <textarea name="{{ $p['key'] }}" rows="3"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none resize-none
                                         focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 bg-gray-50 focus:bg-white
                                         transition-all leading-relaxed
                                         {{ $errors->has($p['key']) ? 'border-red-400 bg-red-50' : '' }}"
                                  >{{ old($p['key'], $config[$p['key']]) }}</textarea>
                        @error($p['key'])
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- TAB 3: MENSAJES ALUMNO → ADMIN                             --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'alumno'" x-cloak>

            {{-- Info --}}
            <div class="mb-4 px-4 py-3 bg-violet-50 border border-violet-100 rounded-2xl flex items-start gap-2.5">
                <svg class="w-4 h-4 text-violet-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-violet-700">
                    Estos mensajes aparecen <strong>pre-cargados en WhatsApp</strong> cuando el alumno pulsa los botones del panel.
                    Puedes usar <code class="bg-violet-100 px-1 py-0.5 rounded font-mono">{nombre}</code> para el nombre del alumno.
                </p>
            </div>

            @php
            $plantillasAlumno = [
                ['key'   => 'wa_alumno_caducada',
                 'emoji' => '🔴',
                 'titulo'=> 'Membresía caducada',
                 'desc'  => 'Se carga cuando el alumno pulsa el botón de renovar (sin matrícula activa)',
                 'donde' => 'Dashboard · Mis Cursos · Botón flotante'],
                ['key'   => 'wa_alumno_suspendida',
                 'emoji' => '⏸️',
                 'titulo'=> 'Acceso suspendido',
                 'desc'  => 'Se carga cuando el acceso del alumno está suspendido por cuotas vencidas',
                 'donde' => 'Banner rojo en el Dashboard'],
            ];
            @endphp

            <div class="space-y-4">
                @foreach($plantillasAlumno as $p)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">{{ $p['emoji'] }}</span>
                                <div>
                                    <h4 class="text-sm font-black text-gray-800">{{ $p['titulo'] }}</h4>
                                    <p class="text-xs text-gray-400">{{ $p['desc'] }}</p>
                                </div>
                            </div>
                            <span class="flex-shrink-0 px-2.5 py-1 bg-violet-50 text-violet-600 text-[10px] font-bold rounded-lg border border-violet-100">
                                {{ $p['donde'] }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <textarea name="{{ $p['key'] }}" rows="3"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none resize-none
                                         focus:border-[#25D366] focus:ring-2 focus:ring-[#25D366]/20 bg-gray-50 focus:bg-white
                                         transition-all leading-relaxed
                                         {{ $errors->has($p['key']) ? 'border-red-400 bg-red-50' : '' }}"
                                  >{{ old($p['key'], $config[$p['key']]) }}</textarea>
                        @error($p['key'])
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        {{-- Preview del link --}}
                        @if($config['whatsapp_number'])
                        <a href="{{ wa_url($config[$p['key']]) }}" target="_blank" rel="noopener"
                           class="mt-2 inline-flex items-center gap-1.5 text-xs text-[#25D366] hover:underline font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                <path d="{{ $waIconPath }}"/>
                            </svg>
                            Previsualizar mensaje →
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- TAB 4: MENSAJE DE BIENVENIDA                               --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'bienvenida'" x-cloak>

            {{-- Info --}}
            <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-start gap-2.5">
                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-xs text-emerald-800">
                    <p>Mensaje que se carga al pulsar <strong>🎓 Bienvenida</strong> en el modal de WhatsApp de matrículas. Variables disponibles:</p>
                    <div class="flex flex-wrap gap-1.5 mt-1.5">
                        @foreach(['{nombre}' => 'Nombre del alumno', '{email}' => 'Correo de acceso', '{password}' => 'Contraseña temporal (DNI)', '{url}' => 'URL de la plataforma'] as $var => $desc)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-emerald-100 border border-emerald-200">
                                <code class="font-mono font-bold text-emerald-700">{{ $var }}</code>
                                <span class="text-emerald-600">— {{ $desc }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Aviso contraseña temporal --}}
            <div class="mb-4 px-4 py-3 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-2.5">
                <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <p class="text-xs text-amber-800">
                    La contraseña inicial del alumno es su <strong>número de DNI</strong>. Se recomienda incluir
                    <code class="bg-amber-100 px-1 rounded font-mono">{password}</code> en el mensaje
                    y recordarle que la cambie por seguridad.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-3">
                    <span class="text-xl">🎓</span>
                    <div>
                        <h4 class="text-sm font-black text-gray-800">Mensaje de bienvenida</h4>
                        <p class="text-xs text-gray-400">Se envía al alumno recién matriculado con sus credenciales de acceso</p>
                    </div>
                    <span class="ml-auto flex-shrink-0 px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-lg border border-emerald-100">
                        Matrículas · Plantilla Bienvenida
                    </span>
                </div>
                <div class="p-4">
                    <textarea name="wa_msg_bienvenida" rows="7"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none resize-none
                                     focus:border-[#25D366] focus:ring-2 focus:ring-[#25D366]/20 bg-gray-50 focus:bg-white
                                     transition-all leading-relaxed
                                     {{ $errors->has('wa_msg_bienvenida') ? 'border-red-400 bg-red-50' : '' }}"
                              >{{ old('wa_msg_bienvenida', $config['wa_msg_bienvenida']) }}</textarea>
                    @error('wa_msg_bienvenida')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    @if($config['whatsapp_number'])
                    <a href="{{ wa_url(wa_plantilla_bienvenida('Alumno Ejemplo', 'alumno@ejemplo.com', '12345678')) }}"
                       target="_blank" rel="noopener"
                       class="mt-2 inline-flex items-center gap-1.5 text-xs text-[#25D366] hover:underline font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                            <path d="{{ $waIconPath }}"/>
                        </svg>
                        Previsualizar mensaje →
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Guardar (siempre visible) --}}
        <div class="flex justify-end mt-6">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-white
                           bg-gradient-to-r from-primary-dark to-primary-light
                           hover:from-accent hover:to-secondary
                           shadow-md hover:shadow-lg hover:-translate-y-0.5
                           transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar configuración
            </button>
        </div>
    </form>

</div>

@endsection
