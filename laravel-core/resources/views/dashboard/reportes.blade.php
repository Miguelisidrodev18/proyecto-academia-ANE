@extends('layouts.dashboard')
@section('title', 'Reportes')

@push('styles')
<style>
.search-ring:focus-within { box-shadow: 0 0 0 3px rgba(48,169,217,0.2); border-color: #30A9D9; }
.clase-card:hover  { border-color: #30A9D9; background: #f0faff; }
.clase-card.active { border-color: #30A9D9; background: #e8f6ff; }
.curso-card:hover  { border-color: #30A9D9; background: #f0faff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(48,169,217,0.15); }
.curso-card.active { border-color: #30A9D9; background: #e8f6ff; box-shadow: 0 0 0 3px rgba(48,169,217,0.15); }
</style>
@endpush

@php
$cursosJson  = $cursos->map(fn($c) => [
    'id'           => $c->id,
    'nombre'       => $c->nombre,
    'nivel'        => ucfirst($c->nivel),
    'tipo'         => ucfirst($c->tipo ?? '—'),
    'clases_count' => $c->clases_count,
    'clases'       => $c->clases->map(fn($cl) => [
        'id'                => $cl->id,
        'titulo'            => $cl->titulo,
        'fecha'             => $cl->fecha->format('d/m/Y'),
        'hora'              => $cl->fecha->format('H:i'),
        'dia'               => $cl->fecha->translatedFormat('l'),
        'asistencias_count' => $cl->asistencias_count,
    ])->values(),
])->values()->toJson();

$alumnosJson = $alumnos->map(fn($a) => [
    'id'      => $a->id,
    'nombre'  => $a->nombreCompleto(),
    'dni'     => $a->dni,
    'inicial' => $a->inicial(),
    'tipo'    => ucfirst($a->tipo),
])->values()->toJson();
@endphp

@push('scripts')
<script>
window._reportesConfig = {
    exportarUrl:      @json(route('reportes.exportar')),
    cursosAlumnoBase: @json(url('/reportes/alumno')),
    allCursos:        {!! $cursosJson !!},
    allAlumnos:       {!! $alumnosJson !!},
};
</script>
@endpush

@section('content')
<div x-data="reportes(window._reportesConfig)">

{{-- ── HEADER ─────────────────────────────────────────────────────────── --}}
<div class="relative rounded-2xl overflow-hidden mb-6 shadow-lg"
     style="background: linear-gradient(135deg, #082B59 0%, #1a5ba0 50%, #30A9D9 100%);">
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-10 -right-10 w-48 h-48 rounded-full bg-white/5"></div>
        <div class="absolute bottom-0 left-1/3 w-32 h-32 rounded-full bg-white/5"></div>
    </div>
    <div class="relative z-10 px-6 py-6 md:px-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-white/15 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h1 class="text-xl font-black text-white tracking-tight">Reportes Académicos</h1>
            </div>
            <p class="text-white/55 text-xs">Exporta asistencia en Excel (.xlsx) o PDF con colores institucionales</p>
        </div>
        <div class="flex gap-2 flex-shrink-0">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white/10 text-white/75 text-xs font-medium">
                <span class="w-2 h-2 rounded-full bg-green-400 flex-shrink-0"></span> Excel
            </span>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white/10 text-white/75 text-xs font-medium">
                <span class="w-2 h-2 rounded-full bg-red-400 flex-shrink-0"></span> PDF
            </span>
        </div>
    </div>
</div>

{{-- ── TABS ────────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow overflow-hidden">

    {{-- Tab nav --}}
    <div class="flex border-b border-gray-100">
        @foreach([
            ['id'=>'alumno',  'label'=>'Por Alumno',        'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ['id'=>'clase',   'label'=>'Por Clase',         'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['id'=>'resumen', 'label'=>'Resumen del Curso', 'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        ] as $t)
        <button @click="setTab('{{ $t['id'] }}')"
                :style="tab==='{{ $t['id'] }}' ? 'border-bottom:2px solid #30A9D9;color:#082B59' : 'border-bottom:2px solid transparent'"
                class="flex-1 flex flex-col items-center gap-1 py-3.5 px-2 text-gray-400 hover:text-gray-600 transition-all duration-150 focus:outline-none text-xs font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" :class="tab==='{{ $t['id'] }}' ? 'text-primary-light' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $t['icon'] }}"/>
            </svg>
            {{ $t['label'] }}
        </button>
        @endforeach
    </div>

    {{-- ════════════════════════════════════════════════
         TAB 1: POR ALUMNO
    ════════════════════════════════════════════════ --}}
    <div x-show="tab==='alumno'" x-cloak class="p-5 sm:p-7"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0">

        {{-- Pasos --}}
        <div class="flex items-center gap-2 mb-6 text-xs">
            <div class="flex items-center gap-1.5" :class="!alumnoSel ? 'text-primary-dark font-bold' : 'text-green-600 font-medium'">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                      :style="!alumnoSel ? 'background:#082B59' : 'background:#10B981'">
                    <template x-if="alumnoSel"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></template>
                    <template x-if="!alumnoSel"><span>1</span></template>
                </span>
                Alumno
            </div>
            <div class="flex-1 h-px" :class="alumnoSel ? 'bg-green-300' : 'bg-gray-200'"></div>
            <div class="flex items-center gap-1.5" :class="alumnoSel && !cursoAlumnoSel ? 'text-primary-dark font-bold' : (cursoAlumnoSel ? 'text-green-600 font-medium' : 'text-gray-400')">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                      :style="cursoAlumnoSel ? 'background:#10B981' : (alumnoSel ? 'background:#082B59' : 'background:#D1D5DB')">
                    <template x-if="cursoAlumnoSel"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></template>
                    <template x-if="!cursoAlumnoSel"><span>2</span></template>
                </span>
                Curso
            </div>
            <div class="flex-1 h-px" :class="cursoAlumnoSel ? 'bg-green-300' : 'bg-gray-200'"></div>
            <div class="flex items-center gap-1.5" :class="cursoAlumnoSel ? 'text-primary-dark font-bold' : 'text-gray-400'">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                      :style="cursoAlumnoSel ? 'background:#082B59' : 'background:#D1D5DB'">3</span>
                Exportar
            </div>
        </div>

        {{-- PASO 1: Buscar alumno --}}
        <div class="mb-5">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">1 — Buscar alumno</label>

            {{-- Tarjeta seleccionado --}}
            <div x-show="alumnoSel" class="flex items-center gap-3 p-3 rounded-xl border border-green-200 bg-green-50 mb-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                     style="background: linear-gradient(135deg,#082B59,#30A9D9)" x-text="alumnoSel?.inicial"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate" x-text="alumnoSel?.nombre"></p>
                    <p class="text-xs text-gray-500">DNI: <span x-text="alumnoSel?.dni"></span> · <span x-text="alumnoSel?.tipo"></span></p>
                </div>
                <button @click="clearAlumno()" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Buscador --}}
            <div x-show="!alumnoSel" class="relative" @click.away="alumnoOpen=false">
                <div class="flex items-center gap-2 px-3 py-2.5 border border-gray-200 rounded-xl bg-white search-ring transition-all">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="alumnoQ"
                           @focus="alumnoOpen=true" @keydown.escape="alumnoOpen=false"
                           placeholder="Escribe nombre o DNI del alumno..."
                           class="flex-1 text-sm outline-none bg-transparent placeholder-gray-400 min-w-0"/>
                    <svg x-show="alumnoQ" @click="alumnoQ=''" class="w-4 h-4 text-gray-400 cursor-pointer hover:text-gray-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>

                {{-- Dropdown resultados --}}
                <div x-show="alumnoOpen && alumnosFiltrados.length > 0"
                     class="absolute z-20 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden max-h-60 overflow-y-auto">
                    <template x-for="a in alumnosFiltrados" :key="a.id">
                        <button @click="selectAlumno(a)" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 transition-colors text-left">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                 style="background:linear-gradient(135deg,#082B59,#30A9D9)" x-text="a.inicial"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate" x-text="a.nombre"></p>
                                <p class="text-xs text-gray-400">DNI: <span x-text="a.dni"></span> · <span x-text="a.tipo"></span></p>
                            </div>
                        </button>
                    </template>
                </div>
                <p x-show="alumnoOpen && alumnoQ.length > 0 && alumnosFiltrados.length === 0"
                   class="absolute z-20 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl px-4 py-3 text-sm text-gray-400 text-center">
                    Sin resultados para "<span x-text="alumnoQ" class="font-medium text-gray-600"></span>"
                </p>
            </div>
        </div>

        {{-- PASO 2: Seleccionar curso (visible tras elegir alumno) --}}
        <div x-show="alumnoSel" x-transition class="mb-5">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">2 — Seleccionar curso</label>

            {{-- Curso seleccionado --}}
            <div x-show="cursoAlumnoSel" class="flex items-center gap-3 p-3 rounded-xl border border-green-200 bg-green-50 mb-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#082B59,#30A9D9)">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate" x-text="cursoAlumnoSel?.nombre"></p>
                    <p class="text-xs text-gray-500"><span x-text="cursoAlumnoSel?.nivel"></span> · <span x-text="cursoAlumnoSel?.tipo"></span> · <span x-text="cursoAlumnoSel?.clases_count"></span> clases</p>
                </div>
                <button @click="cursoAlumnoSel=null" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Loading --}}
            <div x-show="cursosAlumnoLoading && !cursoAlumnoSel" class="flex items-center gap-2 py-4 text-sm text-gray-400">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Cargando cursos del alumno...
            </div>

            {{-- Sin cursos --}}
            <div x-show="!cursosAlumnoLoading && cursosAlumno.length===0 && !cursoAlumnoSel"
                 class="py-4 text-center text-sm text-amber-600 bg-amber-50 rounded-xl border border-amber-100">
                Este alumno no tiene asistencias registradas en ningún curso aún.
            </div>

            {{-- Grid de cursos --}}
            <div x-show="!cursosAlumnoLoading && cursosAlumno.length > 0 && !cursoAlumnoSel"
                 class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <template x-for="c in cursosAlumno" :key="c.id">
                    <button @click="cursoAlumnoSel=c"
                            class="curso-card text-left p-3 rounded-xl border border-gray-200 bg-white transition-all duration-150">
                        <p class="text-sm font-semibold text-gray-800 truncate" x-text="c.nombre"></p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-1.5 py-0.5 rounded-md font-medium bg-blue-100 text-blue-700" x-text="c.nivel"></span>
                            <span class="text-xs text-gray-400" x-text="c.clases_count + ' clases'"></span>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        {{-- PASO 3: Exportar --}}
        <div x-show="alumnoSel && cursoAlumnoSel" x-transition>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">3 — Exportar reporte</label>
            <div class="flex flex-wrap gap-3">
                <a :href="buildUrl('alumno','excel')" target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150"
                   style="background:linear-gradient(135deg,#1D6F42,#27AE60)">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Descargar Excel
                </a>
                <a :href="buildUrl('alumno','pdf')" target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150"
                   style="background:linear-gradient(135deg,#C0392B,#E74C3C)">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Descargar PDF
                </a>
            </div>
        </div>

        @include('reportes._leyenda')
    </div>

    {{-- ════════════════════════════════════════════════
         TAB 2: POR CLASE
    ════════════════════════════════════════════════ --}}
    <div x-show="tab==='clase'" x-cloak class="p-5 sm:p-7"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0">

        {{-- Pasos --}}
        <div class="flex items-center gap-2 mb-6 text-xs">
            <div class="flex items-center gap-1.5" :class="!cursoClasSel ? 'text-primary-dark font-bold' : 'text-green-600 font-medium'">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                      :style="cursoClasSel ? 'background:#10B981' : 'background:#082B59'">
                    <template x-if="cursoClasSel"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></template>
                    <template x-if="!cursoClasSel"><span>1</span></template>
                </span>
                Curso
            </div>
            <div class="flex-1 h-px" :class="cursoClasSel ? 'bg-green-300' : 'bg-gray-200'"></div>
            <div class="flex items-center gap-1.5" :class="cursoClasSel && !claseSel ? 'text-primary-dark font-bold' : (claseSel ? 'text-green-600 font-medium' : 'text-gray-400')">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                      :style="claseSel ? 'background:#10B981' : (cursoClasSel ? 'background:#082B59' : 'background:#D1D5DB')">
                    <template x-if="claseSel"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></template>
                    <template x-if="!claseSel"><span>2</span></template>
                </span>
                Clase
            </div>
            <div class="flex-1 h-px" :class="claseSel ? 'bg-green-300' : 'bg-gray-200'"></div>
            <div class="flex items-center gap-1.5" :class="claseSel ? 'text-primary-dark font-bold' : 'text-gray-400'">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                      :style="claseSel ? 'background:#082B59' : 'background:#D1D5DB'">3</span>
                Exportar
            </div>
        </div>

        {{-- PASO 1: Buscar curso --}}
        <div class="mb-5">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">1 — Buscar curso</label>

            <div x-show="cursoClasSel" class="flex items-center gap-3 p-3 rounded-xl border border-green-200 bg-green-50 mb-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#082B59,#30A9D9)">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate" x-text="cursoClasSel?.nombre"></p>
                    <p class="text-xs text-gray-500"><span x-text="cursoClasSel?.nivel"></span> · <span x-text="cursoClasSel?.clases_count"></span> clases</p>
                </div>
                <button @click="clearCursoClase()" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div x-show="!cursoClasSel" class="relative" @click.away="cursoClasOpen=false">
                <div class="flex items-center gap-2 px-3 py-2.5 border border-gray-200 rounded-xl bg-white search-ring transition-all">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="cursoClasQ" @focus="cursoClasOpen=true" @keydown.escape="cursoClasOpen=false"
                           placeholder="Escribe el nombre del curso..."
                           class="flex-1 text-sm outline-none bg-transparent placeholder-gray-400 min-w-0"/>
                    <svg x-show="cursoClasQ" @click="cursoClasQ=''" class="w-4 h-4 text-gray-400 cursor-pointer hover:text-gray-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div x-show="cursoClasOpen && cursosFiltradosClase.length > 0"
                     class="absolute z-20 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden max-h-56 overflow-y-auto">
                    <template x-for="c in cursosFiltradosClase" :key="c.id">
                        <button @click="selectCursoClase(c)" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 transition-colors text-left">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate" x-text="c.nombre"></p>
                                <p class="text-xs text-gray-400"><span x-text="c.nivel"></span> · <span x-text="c.clases_count"></span> clases registradas</p>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- PASO 2: Seleccionar clase --}}
        <div x-show="cursoClasSel" x-transition class="mb-5">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">2 — Seleccionar clase</label>

            {{-- Clase seleccionada --}}
            <div x-show="claseSel" class="flex items-center gap-3 p-3 rounded-xl border border-green-200 bg-green-50 mb-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-green-200">
                    <svg class="w-4 h-4 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate" x-text="claseSel?.titulo"></p>
                    <p class="text-xs text-gray-500"><span x-text="claseSel?.dia"></span> <span x-text="claseSel?.fecha"></span> · <span x-text="claseSel?.asistencias_count"></span> alumnos registrados</p>
                </div>
                <button @click="claseSel=null" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Sin clases --}}
            <div x-show="cursoClasSel && cursoClasSel.clases.length===0 && !claseSel"
                 class="py-4 text-center text-sm text-amber-600 bg-amber-50 rounded-xl border border-amber-100">
                Este curso no tiene clases registradas aún.
            </div>

            {{-- Lista de clases --}}
            <div x-show="!claseSel && cursoClasSel?.clases.length > 0" class="space-y-1.5 max-h-64 overflow-y-auto pr-1">
                <template x-for="cl in cursoClasSel?.clases ?? []" :key="cl.id">
                    <button @click="claseSel=cl"
                            class="clase-card w-full flex items-center gap-3 p-3 rounded-xl border border-gray-200 bg-white transition-all duration-150 text-left">
                        <div class="w-10 h-10 rounded-lg flex-shrink-0 flex flex-col items-center justify-center text-white" style="background:linear-gradient(135deg,#082B59,#30A9D9)">
                            <span class="text-xs font-bold leading-none" x-text="cl.fecha.split('/')[0]"></span>
                            <span class="text-[9px] leading-none opacity-80" x-text="cl.fecha.split('/')[1]+'/'+cl.fecha.split('/')[2]"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate" x-text="cl.titulo"></p>
                            <p class="text-xs text-gray-400"><span x-text="cl.dia"></span> · <span x-text="cl.hora"></span></p>
                        </div>
                        <span class="flex-shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700" x-text="cl.asistencias_count + ' alum.'"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- PASO 3: Exportar --}}
        <div x-show="claseSel" x-transition>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">3 — Exportar reporte</label>
            <div class="flex flex-wrap gap-3">
                <a :href="buildUrl('clase','excel')" target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150"
                   style="background:linear-gradient(135deg,#1D6F42,#27AE60)">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Descargar Excel
                </a>
                <a :href="buildUrl('clase','pdf')" target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150"
                   style="background:linear-gradient(135deg,#C0392B,#E74C3C)">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Descargar PDF
                </a>
            </div>
        </div>

        @include('reportes._leyenda')
    </div>

    {{-- ════════════════════════════════════════════════
         TAB 3: RESUMEN DEL CURSO
    ════════════════════════════════════════════════ --}}
    <div x-show="tab==='resumen'" x-cloak class="p-5 sm:p-7"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0">

        {{-- Pasos --}}
        <div class="flex items-center gap-2 mb-6 text-xs">
            <div class="flex items-center gap-1.5" :class="!cursoResumenSel ? 'text-primary-dark font-bold' : 'text-green-600 font-medium'">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                      :style="cursoResumenSel ? 'background:#10B981' : 'background:#082B59'">
                    <template x-if="cursoResumenSel"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></template>
                    <template x-if="!cursoResumenSel"><span>1</span></template>
                </span>
                Curso
            </div>
            <div class="flex-1 h-px" :class="cursoResumenSel ? 'bg-green-300' : 'bg-gray-200'"></div>
            <div class="flex items-center gap-1.5" :class="cursoResumenSel ? 'text-primary-dark font-bold' : 'text-gray-400'">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                      :style="cursoResumenSel ? 'background:#082B59' : 'background:#D1D5DB'">2</span>
                Exportar
            </div>
        </div>

        {{-- PASO 1: Buscar curso --}}
        <div class="mb-5">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">1 — Buscar curso</label>

            <div x-show="cursoResumenSel" class="flex items-center gap-3 p-3 rounded-xl border border-green-200 bg-green-50 mb-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#082B59,#30A9D9)">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate" x-text="cursoResumenSel?.nombre"></p>
                    <p class="text-xs text-gray-500"><span x-text="cursoResumenSel?.nivel"></span> · <span x-text="cursoResumenSel?.tipo"></span> · <span x-text="cursoResumenSel?.clases_count"></span> clases</p>
                </div>
                <button @click="cursoResumenSel=null; cursoResumenQ=''" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div x-show="!cursoResumenSel" class="relative" @click.away="cursoResumenOpen=false">
                <div class="flex items-center gap-2 px-3 py-2.5 border border-gray-200 rounded-xl bg-white search-ring transition-all">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="cursoResumenQ" @focus="cursoResumenOpen=true" @keydown.escape="cursoResumenOpen=false"
                           placeholder="Escribe el nombre del curso..."
                           class="flex-1 text-sm outline-none bg-transparent placeholder-gray-400 min-w-0"/>
                    <svg x-show="cursoResumenQ" @click="cursoResumenQ=''" class="w-4 h-4 text-gray-400 cursor-pointer hover:text-gray-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div x-show="cursoResumenOpen && cursosFiltradosResumen.length > 0"
                     class="absolute z-20 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden max-h-56 overflow-y-auto">
                    <template x-for="c in cursosFiltradosResumen" :key="c.id">
                        <button @click="cursoResumenSel=c; cursoResumenOpen=false"
                                class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 transition-colors text-left">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate" x-text="c.nombre"></p>
                                <p class="text-xs text-gray-400"><span x-text="c.nivel"></span> · <span x-text="c.clases_count"></span> clases</p>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Info Excel --}}
        <div x-show="cursoResumenSel" x-transition class="mb-5 p-4 rounded-xl border border-gray-100 bg-gray-50">
            <p class="text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">El Excel incluye 2 hojas:</p>
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="w-5 h-5 rounded text-white font-bold text-xs flex items-center justify-center flex-shrink-0" style="background:#082B59">1</span>
                    <span><strong class="text-gray-800">Resumen por Clase</strong> — presentes, ausentes y % por clase</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="w-5 h-5 rounded text-white font-bold text-xs flex items-center justify-center flex-shrink-0" style="background:#30A9D9">2</span>
                    <span><strong class="text-gray-800">Detalle por Alumno</strong> — matriz P/T/J/A + % total</span>
                </div>
            </div>
        </div>

        {{-- PASO 2: Exportar --}}
        <div x-show="cursoResumenSel" x-transition>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">2 — Exportar reporte</label>
            <div class="flex flex-wrap gap-3">
                <a :href="buildUrl('curso','excel')" target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150"
                   style="background:linear-gradient(135deg,#1D6F42,#27AE60)">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Descargar Excel (2 hojas)
                </a>
                <a :href="buildUrl('curso','pdf')" target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150"
                   style="background:linear-gradient(135deg,#C0392B,#E74C3C)">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Descargar PDF (apaisado)
                </a>
            </div>
        </div>

        @include('reportes._leyenda')
    </div>

</div>{{-- /tabs card --}}
</div>{{-- /x-data --}}
@endsection
