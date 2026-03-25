@extends('layouts.dashboard')
@section('title', 'Nueva Clase')

@section('content')

@php
    $cursosJson = $cursos->map(fn ($c) => [
        'id'            => $c->id,
        'nombre'        => $c->nombre,
        'nivel'         => $c->nivel,
        'nivelLabel'    => $c->nivelLabel(),
        'tipoLabel'     => $c->tipoLabel(),
        'grado'         => $c->grado,
        'gradoLabel'    => $c->gradoLabel(),
        'proximas'      => $c->proximas_count,
        'planes'        => $c->planes->map(fn ($p) => ['nombre' => $p->nombre, 'tipo' => $p->tipo_plan])->values(),
        'proximaFecha'  => optional($c->clases->first())->fecha?->format('d/m/Y H:i'),
    ])->values()->toJson();
@endphp

<div x-data="claseWizard({{ $cursosJson }}, '{{ $cursoSeleccionadoId }}')" class="max-w-5xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('clases.index') }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <nav class="text-xs text-gray-400 mb-0.5">
                <a href="{{ route('clases.index') }}" class="hover:text-accent transition-colors">Clases</a>
                <span class="mx-1">/</span>
                <span class="text-gray-600">Nueva clase</span>
            </nav>
            <h1 class="text-xl font-black text-primary-dark leading-none">Nueva Clase</h1>
        </div>

        {{-- Indicador de pasos --}}
        <div class="hidden sm:flex items-center gap-2">
            <div class="flex items-center gap-1.5">
                <div :class="step >= 1 ? 'bg-accent text-white' : 'bg-gray-100 text-gray-400'"
                     class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black transition-colors">1</div>
                <span :class="step >= 1 ? 'text-accent' : 'text-gray-400'"
                      class="text-xs font-semibold hidden md:block transition-colors">Curso</span>
            </div>
            <div class="w-8 h-px" :class="step >= 2 ? 'bg-accent' : 'bg-gray-200'"></div>
            <div class="flex items-center gap-1.5">
                <div :class="step >= 2 ? 'bg-accent text-white' : 'bg-gray-100 text-gray-400'"
                     class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black transition-colors">2</div>
                <span :class="step >= 2 ? 'text-accent' : 'text-gray-400'"
                      class="text-xs font-semibold hidden md:block transition-colors">Detalles</span>
            </div>
        </div>
    </div>

    @include('clases._flash')

    <form method="POST" action="{{ route('clases.store') }}">
        @csrf
        <input type="hidden" name="curso_id" :value="cursoId">

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- PASO 1 — Selección visual de curso                            --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div x-show="step === 1"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">

            <p class="text-gray-500 text-sm mb-5">Selecciona el curso al que pertenecerá esta clase:</p>

            {{-- Buscador de cursos --}}
            <div class="relative mb-5">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" x-model="busqueda" placeholder="Buscar curso..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm outline-none
                              focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
            </div>

            {{-- Grid de cursos --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="curso in cursosFiltrados" :key="curso.id">
                    <button type="button"
                            @click="seleccionarCurso(curso)"
                            :class="cursoId == curso.id
                                ? 'ring-2 ring-accent border-accent bg-accent/5 shadow-lg shadow-accent/15'
                                : 'border-gray-100 bg-white hover:border-accent/40 hover:shadow-md hover:-translate-y-0.5'"
                            class="relative text-left rounded-2xl border p-4 transition-all duration-200 cursor-pointer group w-full">

                        {{-- Check de selección --}}
                        <div x-show="cursoId == curso.id"
                             class="absolute top-3 right-3 w-6 h-6 rounded-full bg-accent flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>

                        {{-- Badge de nivel --}}
                        <div class="flex items-center gap-2 mb-3">
                            <span :class="{
                                    'bg-blue-100 text-blue-700'   : curso.nivel === 'pollito',
                                    'bg-accent/10 text-accent'    : curso.nivel === 'intermedio',
                                    'bg-violet-100 text-violet-700': curso.nivel === 'ambos',
                                 }"
                                  class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide"
                                  x-text="curso.nivelLabel"></span>
                            <template x-if="curso.grado">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700"
                                      x-text="curso.gradoLabel"></span>
                            </template>
                        </div>

                        {{-- Nombre --}}
                        <h3 class="font-black text-gray-800 text-sm leading-tight mb-1 pr-7" x-text="curso.nombre"></h3>
                        <p class="text-xs text-gray-400 mb-3" x-text="curso.tipoLabel"></p>

                        {{-- Planes --}}
                        <template x-if="curso.planes.length > 0">
                            <div class="flex flex-wrap gap-1 mb-3">
                                <template x-for="plan in curso.planes" :key="plan.nombre">
                                    <span :class="plan.tipo === 'vip'
                                            ? 'bg-amber-50 text-amber-700 border-amber-200'
                                            : 'bg-primary-dark/5 text-primary-dark/70 border-primary-dark/10'"
                                          class="px-2 py-0.5 rounded-full text-[10px] font-semibold border"
                                          x-text="plan.nombre"></span>
                                </template>
                            </div>
                        </template>

                        {{-- Stats --}}
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-xs text-gray-400">
                                <span class="font-bold text-gray-600" x-text="curso.proximas"></span>
                                próx. clase<span x-show="curso.proximas !== 1">s</span>
                            </span>
                            <template x-if="curso.proximaFecha">
                                <span class="text-[10px] text-accent font-semibold" x-text="'📅 ' + curso.proximaFecha"></span>
                            </template>
                            <template x-if="!curso.proximaFecha">
                                <span class="text-[10px] text-gray-300">Sin clases agendadas</span>
                            </template>
                        </div>
                    </button>
                </template>

                {{-- Sin resultados --}}
                <template x-if="cursosFiltrados.length === 0">
                    <div class="col-span-full py-12 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-semibold">No hay cursos que coincidan</p>
                    </div>
                </template>
            </div>

            {{-- Botón siguiente --}}
            <div class="flex justify-end mt-6">
                <button type="button"
                        @click="step = 2"
                        :disabled="!cursoId"
                        :class="cursoId
                            ? 'bg-gradient-to-r from-primary-dark to-primary-light text-white shadow-md hover:from-accent hover:to-secondary hover:shadow-lg hover:-translate-y-0.5'
                            : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                        class="flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm transition-all duration-200">
                    Continuar
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- PASO 2 — Detalles de la clase                                 --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div x-show="step === 2"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Curso seleccionado (resumen) --}}
            <div class="flex items-center gap-3 mb-6 p-4 rounded-2xl border border-accent/20 bg-accent/5">
                <div class="w-10 h-10 rounded-xl bg-accent/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-accent font-semibold uppercase tracking-wide">Curso seleccionado</p>
                    <p class="font-black text-gray-800 text-sm" x-text="cursoActual?.nombre"></p>
                    <p class="text-xs text-gray-400" x-text="cursoActual?.nivelLabel + ' · ' + cursoActual?.tipoLabel"></p>
                </div>
                <button type="button" @click="step = 1"
                        class="text-xs text-gray-400 hover:text-accent font-semibold px-3 py-1.5 rounded-lg hover:bg-accent/10 transition-all flex-shrink-0">
                    Cambiar
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Formulario principal --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Card: Info básica --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Información básica</h3>

                        {{-- Título --}}
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Título de la clase <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="titulo" value="{{ old('titulo') }}"
                                   x-model="titulo"
                                   placeholder="Ej: Clase 1 — Introducción al Álgebra"
                                   autocomplete="off"
                                   class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                                          {{ $errors->has('titulo') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
                            @error('titulo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fecha --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Fecha y hora <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="fecha" value="{{ old('fecha') }}"
                                   x-model="fecha"
                                   class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                                          {{ $errors->has('fecha') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
                            @error('fecha')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            {{-- Label dinámico del día --}}
                            <p x-show="fecha" class="text-xs text-accent font-semibold mt-1.5"
                               x-text="'📅 ' + diaSemana"></p>
                        </div>
                    </div>

                    {{-- Card: Extras colapsables --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
                         x-data="{ abierto: {{ $errors->has('descripcion') || $errors->has('grabacion_url') || old('descripcion') || old('grabacion_url') ? 'true' : 'false' }} }">

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

                            {{-- Descripción --}}
                            <div class="pt-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Descripción</label>
                                <textarea name="descripcion" rows="3"
                                          placeholder="Temas que se verán en esta clase..."
                                          class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all resize-none
                                                 border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20">{{ old('descripcion') }}</textarea>
                            </div>

                            {{-- URL Grabación --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    URL de grabación
                                    <span class="text-gray-400 font-normal text-xs ml-1">(completar después)</span>
                                </label>
                                <input type="url" name="grabacion_url" value="{{ old('grabacion_url') }}"
                                       placeholder="https://drive.google.com/... o https://youtube.com/..."
                                       class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                                              {{ $errors->has('grabacion_url') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
                                @error('grabacion_url')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-400 mt-1">Al ingresar una URL, la clase se marcará como grabada automáticamente.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar: Clases próximas del curso seleccionado --}}
                <div class="space-y-4">

                    {{-- Próximas clases del curso --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Próximas clases del curso</h3>

                        <template x-if="cursoActual && cursoActual.proximas === 0">
                            <div class="py-4 text-center">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-400">Sin clases agendadas.<br>¡Será la primera!</p>
                            </div>
                        </template>

                        <template x-if="cursoActual && cursoActual.proximas > 0">
                            <div>
                                @foreach($cursos as $curso)
                                <div x-show="cursoId == {{ $curso->id }}" class="space-y-2">
                                    @forelse($curso->clases->take(3) as $clase)
                                    <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50">
                                        <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-gray-700 leading-tight truncate">{{ $clase->titulo }}</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $clase->fecha->format('d/m/Y · H:i') }}</p>
                                        </div>
                                    </div>
                                    @empty
                                    @endforelse
                                </div>
                                @endforeach

                                <template x-if="cursoActual && cursoActual.proximas > 3">
                                    <p class="text-[10px] text-gray-400 text-center pt-2"
                                       x-text="'+ ' + (cursoActual.proximas - 3) + ' más agendadas'"></p>
                                </template>
                            </div>
                        </template>

                        <template x-if="!cursoActual">
                            <p class="text-xs text-gray-300 text-center py-4">Selecciona un curso para ver sus clases.</p>
                        </template>
                    </div>

                    {{-- Tip --}}
                    <div class="bg-accent/5 border border-accent/15 rounded-2xl p-4">
                        <p class="text-xs font-bold text-accent mb-1">💡 Tip</p>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            El link de Zoom se configura a nivel del curso. La grabación se puede añadir después de que se realice la clase.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-between mt-6">
                <button type="button" @click="step = 1"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Volver
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('clases.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                            :disabled="!titulo || !fecha"
                            :class="titulo && fecha
                                ? 'bg-gradient-to-r from-primary-dark to-primary-light text-white shadow-md hover:from-accent hover:to-secondary hover:shadow-lg hover:-translate-y-0.5'
                                : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Crear clase
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
function claseWizard(cursos, cursoSeleccionadoId) {
    return {
        step: cursoSeleccionadoId ? 2 : 1,
        cursos,
        cursoId: cursoSeleccionadoId || '',
        busqueda: '',
        titulo: '{{ old('titulo') }}',
        fecha: '{{ old('fecha') }}',

        get cursoActual() {
            return this.cursos.find(c => c.id == this.cursoId) || null;
        },

        get cursosFiltrados() {
            if (!this.busqueda.trim()) return this.cursos;
            const q = this.busqueda.toLowerCase();
            return this.cursos.filter(c =>
                c.nombre.toLowerCase().includes(q) ||
                c.nivelLabel.toLowerCase().includes(q) ||
                c.planes.some(p => p.nombre.toLowerCase().includes(q))
            );
        },

        get diaSemana() {
            if (!this.fecha) return '';
            const dias = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
            const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
            const d = new Date(this.fecha);
            return `${dias[d.getDay()]}, ${d.getDate()} de ${meses[d.getMonth()]} ${d.getFullYear()} · ${String(d.getHours()).padStart(2,'0')}:${String(d.getMinutes()).padStart(2,'0')}`;
        },

        seleccionarCurso(curso) {
            this.cursoId = curso.id;
        },
    };
}
</script>

@endsection
