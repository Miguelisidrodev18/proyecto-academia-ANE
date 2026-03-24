@php
    $curso_id          = old('curso_id',          $material->curso_id          ?? ($cursoSeleccionado->id ?? ''));
    $titulo            = old('titulo',            $material->titulo            ?? '');
    $tipo              = old('tipo',              $material->tipo              ?? 'pdf');
    $url               = old('url',               $material->url               ?? '');
    $fecha_publicacion = old('fecha_publicacion', isset($material) ? $material->fecha_publicacion->format('Y-m-d') : now()->format('Y-m-d'));
    $descripcion       = old('descripcion',       $material->descripcion       ?? '');
    $visible           = old('visible',           $material->visible           ?? true);
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-5">Información del material</h2>

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
                   placeholder="Ej: Guía de ejercicios — Álgebra Semana 1"
                   class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                          {{ $errors->has('titulo') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            @error('titulo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tipo --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Tipo <span class="text-red-500">*</span>
            </label>
            <select name="tipo"
                    class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                           {{ $errors->has('tipo') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
                <option value="pdf"    {{ $tipo === 'pdf'    ? 'selected' : '' }}>📄 PDF</option>
                <option value="video"  {{ $tipo === 'video'  ? 'selected' : '' }}>🎥 Video</option>
                <option value="enlace" {{ $tipo === 'enlace' ? 'selected' : '' }}>🔗 Enlace</option>
                <option value="imagen" {{ $tipo === 'imagen' ? 'selected' : '' }}>🖼️ Imagen</option>
                <option value="otro"   {{ $tipo === 'otro'   ? 'selected' : '' }}>📎 Otro</option>
            </select>
            @error('tipo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Fecha de publicación --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Fecha de publicación <span class="text-red-500">*</span>
            </label>
            <input type="date" name="fecha_publicacion" value="{{ $fecha_publicacion }}"
                   class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                          {{ $errors->has('fecha_publicacion') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            @error('fecha_publicacion')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- URL --}}
        <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                URL del material <span class="text-red-500">*</span>
            </label>
            <input type="url" name="url" value="{{ $url }}"
                   placeholder="https://drive.google.com/... o https://youtube.com/..."
                   class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all
                          {{ $errors->has('url') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            @error('url')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-400 mt-1">Link de Google Drive, YouTube, Dropbox, etc.</p>
        </div>

        {{-- Descripción --}}
        <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Descripción</label>
            <textarea name="descripcion" rows="2"
                      placeholder="Descripción breve del contenido..."
                      class="w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all resize-none
                             border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20">{{ $descripcion }}</textarea>
        </div>

        {{-- Visible --}}
        <div x-data="{ visible: {{ $visible ? 'true' : 'false' }} }" class="sm:col-span-2">
            <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Visible para alumnos</p>
                    <p class="text-xs text-gray-400 mt-0.5">Los alumnos del curso podrán ver este material en su panel.</p>
                </div>
                <button type="button" @click="visible = !visible"
                        :class="visible ? 'bg-accent' : 'bg-gray-300'"
                        class="relative inline-flex w-12 h-6 rounded-full transition-colors duration-200 focus:outline-none flex-shrink-0 ml-4">
                    <span :class="visible ? 'translate-x-6' : 'translate-x-1'"
                          class="inline-block w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200 mt-1"></span>
                </button>
                <input type="hidden" name="visible" :value="visible ? '1' : '0'">
            </label>
        </div>

    </div>
</div>
