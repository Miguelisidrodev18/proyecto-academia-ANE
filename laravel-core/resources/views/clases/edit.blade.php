@extends('layouts.dashboard')
@section('title', 'Editar Clase')

@section('content')

<div class="max-w-5xl mx-auto" x-data="{
    fecha: '{{ old('fecha', $clase->fecha->format('Y-m-d\TH:i')) }}',
    titulo: '{{ old('titulo', addslashes($clase->titulo)) }}',
    get diaSemana() {
        if (!this.fecha) return '';
        const dias = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
        const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
        const d = new Date(this.fecha);
        return `${dias[d.getDay()]}, ${d.getDate()} de ${meses[d.getMonth()]} ${d.getFullYear()} · ${String(d.getHours()).padStart(2,'0')}:${String(d.getMinutes()).padStart(2,'0')}`;
    }
}">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('cursos.show', $clase->curso) }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('cursos.index') }}" class="hover:text-accent transition-colors">Cursos</a>
                <span class="mx-1">/</span>
                <a href="{{ route('cursos.show', $clase->curso) }}" class="hover:text-accent transition-colors">{{ $clase->curso->nombre }}</a>
                <span class="mx-1">/</span>
                <span class="text-gray-600">Editar clase</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">Editar Clase</h1>
        </div>
    </div>

    @include('clases._flash')

    <form method="POST" action="{{ route('clases.update', $clase) }}">
        @csrf @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Formulario principal --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Curso (solo lectura, no se puede cambiar en edición) --}}
                <div class="flex items-center gap-3 p-4 rounded-2xl border border-accent/20 bg-accent/5">
                    <div class="w-10 h-10 rounded-xl bg-accent/15 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-accent font-semibold uppercase tracking-wide">Curso</p>
                        <p class="font-black text-gray-800 text-sm">{{ $clase->curso->nombre }}</p>
                        <p class="text-xs text-gray-400">{{ $clase->curso->nivelLabel() }} · {{ $clase->curso->tipoLabel() }}</p>
                    </div>
                    {{-- El curso_id se mantiene oculto --}}
                    <input type="hidden" name="curso_id" value="{{ $clase->curso_id }}">
                    <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-1 rounded-lg flex-shrink-0">Fijo</span>
                </div>

                {{-- Card: Info básica --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Información básica</h3>

                    {{-- Título --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Título <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="titulo" x-model="titulo"
                               value="{{ old('titulo', $clase->titulo) }}"
                               class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                                      {{ $errors->has('titulo') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
                        @error('titulo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Fecha y hora <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="fecha" x-model="fecha"
                               value="{{ old('fecha', $clase->fecha->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                                      {{ $errors->has('fecha') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
                        @error('fecha') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p x-show="fecha" class="text-xs text-accent font-semibold mt-1.5" x-text="'📅 ' + diaSemana"></p>
                    </div>
                </div>

                {{-- Card: Extras --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
                     x-data="{ abierto: {{ $clase->descripcion || $clase->grabacion_url || $errors->hasAny(['descripcion','grabacion_url']) ? 'true' : 'false' }} }">

                    <button type="button" @click="abierto = !abierto"
                            class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors">
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Opciones adicionales</h3>
                            <p x-show="!abierto" class="text-xs text-gray-400 mt-0.5">Descripción · URL de grabación</p>
                        </div>
                        <svg :class="abierto ? 'rotate-180' : ''"
                             class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="abierto"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="px-5 pb-5 space-y-4 border-t border-gray-100">

                        <div class="pt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Descripción</label>
                            <textarea name="descripcion" rows="3"
                                      class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all resize-none
                                             border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20">{{ old('descripcion', $clase->descripcion) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                URL de grabación
                                @if($clase->grabada)
                                    <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Grabada</span>
                                @endif
                            </label>
                            <input type="url" name="grabacion_url" value="{{ old('grabacion_url', $clase->grabacion_url) }}"
                                   placeholder="https://drive.google.com/... o https://youtube.com/..."
                                   class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                                          {{ $errors->has('grabacion_url') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
                            @error('grabacion_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">

                {{-- Info de la clase --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Estado actual</h3>

                    <div class="space-y-3">
                        @php
                            $esFutura = $clase->fecha->isFuture();
                            $diasDiff = now()->diffInDays($clase->fecha, false);
                        @endphp
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Estado</span>
                            @if($esFutura)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                    En {{ abs($diasDiff) }} días
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">
                                    Realizada
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Zoom del curso</span>
                            @if($clase->curso->zoom_link)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">Configurado ✓</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600">Sin configurar</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Grabación</span>
                            @if($clase->grabada && $clase->grabacion_url)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-violet-50 text-violet-700">Disponible ✓</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-400">Sin grabación</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Asistencia</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">
                                {{ $clase->asistencias()->count() }} registros
                            </span>
                        </div>
                    </div>

                    @if($clase->curso->zoom_link)
                        <a href="{{ $clase->curso->zoom_link }}" target="_blank"
                           class="mt-4 w-full flex items-center justify-center gap-2 py-2 rounded-xl text-xs font-bold text-white
                                  bg-gradient-to-r from-primary-dark to-primary-light hover:from-accent hover:to-secondary transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Abrir Zoom del curso
                        </a>
                    @endif
                </div>

                {{-- Tip --}}
                <div class="bg-accent/5 border border-accent/15 rounded-2xl p-4">
                    <p class="text-xs font-bold text-accent mb-1">💡 Tip</p>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Para cambiar el curso de esta clase, elimínala y crea una nueva en el curso correcto.
                    </p>
                </div>
            </div>
        </div>

        {{-- Botones --}}
        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('cursos.show', $clase->curso) }}"
               class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm text-white
                           bg-gradient-to-r from-primary-dark to-primary-light
                           hover:from-accent hover:to-secondary transition-all duration-300 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar cambios
            </button>
        </div>
    </form>

</div>
@endsection
