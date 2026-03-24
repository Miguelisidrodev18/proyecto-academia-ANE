@php
    $curso_id      = old('curso_id',      $clase->curso_id      ?? ($cursoSeleccionado->id ?? ''));
    $titulo        = old('titulo',        $clase->titulo        ?? '');
    $fecha         = old('fecha',         isset($clase) ? $clase->fecha->format('Y-m-d\TH:i') : '');
    $zoom_link     = old('zoom_link',     $clase->zoom_link     ?? '');
    $descripcion   = old('descripcion',   $clase->descripcion   ?? '');
    $grabacion_url = old('grabacion_url', $clase->grabacion_url ?? '');
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-5">Información de la clase</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

        {{-- Curso --}}
        <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Curso <span class="text-red-500">*</span>
            </label>
            <select name="curso_id"
                    class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                           {{ $errors->has('curso_id') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
                <option value="">Seleccionar curso...</option>
                @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}" {{ $curso_id == $curso->id ? 'selected' : '' }}>
                        {{ $curso->nombre }} — {{ $curso->nivelLabel() }}
                    </option>
                @endforeach
            </select>
            @error('curso_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Título --}}
        <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Título <span class="text-red-500">*</span>
            </label>
            <input type="text" name="titulo" value="{{ $titulo }}"
                   placeholder="Ej: Clase 1 — Introducción a Álgebra"
                   class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                          {{ $errors->has('titulo') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            @error('titulo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Fecha y hora --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Fecha y hora <span class="text-red-500">*</span>
            </label>
            <input type="datetime-local" name="fecha" value="{{ $fecha }}"
                   class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                          {{ $errors->has('fecha') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            @error('fecha')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Link de Zoom --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Link de Zoom
            </label>
            <input type="url" name="zoom_link" value="{{ $zoom_link }}"
                   placeholder="https://zoom.us/j/..."
                   class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                          {{ $errors->has('zoom_link') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            @error('zoom_link')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Descripción</label>
            <textarea name="descripcion" rows="3"
                      placeholder="Temas a tratar en esta clase..."
                      class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all resize-none
                             border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20">{{ $descripcion }}</textarea>
        </div>

        {{-- URL de grabación --}}
        <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                URL de grabación
                <span class="text-gray-400 font-normal">(completar después de la clase)</span>
            </label>
            <input type="url" name="grabacion_url" value="{{ $grabacion_url }}"
                   placeholder="https://drive.google.com/... o https://youtube.com/..."
                   class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                          {{ $errors->has('grabacion_url') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            @error('grabacion_url')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-400 mt-1">Al guardar una URL de grabación, la clase quedará marcada como grabada automáticamente.</p>
        </div>

    </div>
</div>
