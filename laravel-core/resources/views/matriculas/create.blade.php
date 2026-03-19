@extends('layouts.dashboard')
@section('title', 'Nueva Matrícula')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    .select2-container--default .select2-selection--single {
        height: auto; padding: 0.625rem 1rem; border-radius: 0.75rem;
        border: 1px solid #e5e7eb; background-color: #f9fafb;
        font-size: 0.875rem; line-height: 1.5; transition: border-color .15s, box-shadow .15s;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered { color: #1f2937; line-height: 1.5; padding: 0; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 100%; top: 0; right: 0.75rem; }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #0BC4D9; box-shadow: 0 0 0 3px rgba(11,196,217,.15); background-color: #fff; outline: none;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder { color: #9ca3af; }
    .select2-dropdown { border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 10px 25px -5px rgba(0,0,0,.1); overflow: hidden; margin-top: 4px; }
    .select2-container--default .select2-search--dropdown .select2-search__field { border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 0.5rem 0.75rem; font-size: 0.875rem; outline: none; }
    .select2-container--default .select2-search--dropdown .select2-search__field:focus { border-color: #0BC4D9; box-shadow: 0 0 0 2px rgba(11,196,217,.2); }
    .select2-container--default .select2-results__option { padding: 0.625rem 1rem; font-size: 0.875rem; }
    .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: #0BC4D9; color: #fff; }
    .select2-container--default .select2-results__option[aria-selected=true] { background-color: #f0fdfe; color: #082B59; font-weight: 600; }
    .select2-container { width: 100% !important; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function () {
    var $select = $('#alumno_select');

    $select.select2({
        placeholder: 'Buscar alumno por nombre o DNI',
        allowClear: true,
        minimumInputLength: 1,
        language: {
            inputTooShort: function () { return 'Escribe al menos 1 carácter...'; },
            searching: function () { return 'Buscando...'; },
            noResults: function () { return 'No se encontraron alumnos'; },
        },
        ajax: {
            url: '{{ route("alumnos.buscar") }}',
            dataType: 'json',
            delay: 300,
            data: function (params) { return { q: params.term }; },
            processResults: function (data) { return { results: data }; },
            cache: true,
        },
    });

    function verificarMatriculaActiva(alumnoId) {
        fetch('/alumnos/' + alumnoId + '/matriculas-activas', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        })
        .then(function (r) { return r.json(); })
        .then(function (resp) {
            if (resp.tieneActiva) {
                document.getElementById('adv-plan').textContent   = resp.matricula.plan_nombre;
                document.getElementById('adv-inicio').textContent = resp.matricula.fecha_inicio;
                document.getElementById('adv-fin').textContent    = resp.matricula.fecha_fin;
                document.getElementById('confirmar_checkbox').checked = false;
                document.getElementById('confirmar_duplicada').value  = '0';
                document.getElementById('advertencia-container').style.display = 'block';
                window.dispatchEvent(new CustomEvent('bloquear-submit', { detail: { bloqueado: true } }));
            } else {
                document.getElementById('advertencia-container').style.display = 'none';
                document.getElementById('confirmar_duplicada').value = '0';
                window.dispatchEvent(new CustomEvent('bloquear-submit', { detail: { bloqueado: false } }));
            }
        });
    }

    $select.on('select2:select', function (e) {
        $('#alumno_id').val(e.params.data.id);
        verificarMatriculaActiva(e.params.data.id);
    });

    $select.on('select2:clear', function () {
        $('#alumno_id').val('');
        document.getElementById('advertencia-container').style.display = 'none';
        document.getElementById('confirmar_duplicada').value = '0';
        window.dispatchEvent(new CustomEvent('bloquear-submit', { detail: { bloqueado: false } }));
    });

    @if(isset($alumnoSeleccionado) && $alumnoSeleccionado)
    // Pre-seleccionar alumno venido desde el flujo rápido
    var preOption = new Option(
        '{{ $alumnoSeleccionado->nombreCompleto() }} - {{ $alumnoSeleccionado->dni }}',
        '{{ $alumnoSeleccionado->id }}',
        true, true
    );
    $select.append(preOption).trigger('change');
    $('#alumno_id').val('{{ $alumnoSeleccionado->id }}');
    verificarMatriculaActiva('{{ $alumnoSeleccionado->id }}');
    @endif
});
</script>
@endpush

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-5 flex-wrap">
        <a href="{{ isset($flowAlumno) ? route('alumnos.credenciales', $flowAlumno) : route('matriculas.index') }}"
           class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-primary-dark hover:border-primary-dark/30 hover:bg-primary-dark/5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-xl font-black text-primary-dark leading-none">Nueva Matrícula</h1>
            @if(request()->boolean('flow'))
                <p class="text-xs text-gray-400 mt-0.5">Paso 2 de 3 · Elige el plan y las fechas</p>
            @endif
        </div>
        <span class="text-xs text-primary-dark/70 font-medium flex items-center gap-1 bg-primary-dark/5 border border-primary-dark/10 px-3 py-1.5 rounded-full">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            El precio se calcula al elegir el plan
        </span>
    </div>

    @if(request()->boolean('flow'))
        @include('partials.flow-stepper', ['flowStep' => 2, 'flowAlumno' => $alumnoSeleccionado ?? null])
    @endif

    @include('matriculas._flash')

    <form method="POST" action="{{ route('matriculas.store') }}"
          x-data="{ bloqueado: false }"
          @bloquear-submit.window="bloqueado = $event.detail.bloqueado">
        @csrf
        @if(request()->boolean('flow'))
            <input type="hidden" name="flow" value="1">
        @endif
        @include('matriculas._form')
        <div class="flex items-center gap-3 mt-5">
            <button type="submit"
                    :disabled="bloqueado"
                    :class="bloqueado
                        ? 'opacity-50 cursor-not-allowed from-gray-400 to-gray-500'
                        : 'hover:from-accent hover:to-secondary hover:shadow-lg hover:-translate-y-0.5'"
                    class="inline-flex items-center gap-2 px-7 py-3 rounded-xl font-bold text-sm text-white
                           bg-gradient-to-r from-primary-dark to-primary-light
                           transition-all duration-300 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <span x-text="bloqueado
                    ? 'Confirma la advertencia para continuar'
                    : '{{ request()->boolean('flow') ? 'Registrar matrícula y continuar al pago' : 'Registrar matrícula' }}'">
                </span>
            </button>
            <a href="{{ route('matriculas.index') }}"
               class="px-6 py-3 rounded-xl font-semibold text-sm text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
