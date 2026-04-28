@php
    $anuncio        ??= null;
    $siguienteOrden ??= 0;
    $editing         = $anuncio !== null;
    $destinos  = ['alumno' => 'Alumnos', 'representante' => 'Representantes'];
    $selDest   = old('destinatarios', $editing ? ($anuncio->destinatarios ?? []) : ['alumno', 'representante']);
@endphp

{{-- Título --}}
<div>
    <label class="block text-xs font-bold text-gray-600 mb-1.5">Título <span class="text-gray-400 font-normal">(opcional)</span></label>
    <input type="text" name="titulo"
           value="{{ old('titulo', $anuncio?->titulo) }}"
           maxlength="120"
           placeholder="Ej: ¡Exámenes de admisión este sábado!"
           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition">
    @error('titulo')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>

{{-- Descripción --}}
<div>
    <label class="block text-xs font-bold text-gray-600 mb-1.5">Descripción <span class="text-gray-400 font-normal">(opcional)</span></label>
    <textarea name="descripcion" rows="3" maxlength="1000"
              placeholder="Texto descriptivo que verán los alumnos..."
              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition resize-none">{{ old('descripcion', $anuncio?->descripcion) }}</textarea>
    @error('descripcion')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>

{{-- Imagen --}}
<div x-data="{
        preview: @js($editing && $anuncio->imagenUrl() ? $anuncio->imagenUrl() : null),
        onChange(e) {
            const file = e.target.files[0];
            if (file) this.preview = URL.createObjectURL(file);
        }
    }">
    <label class="block text-xs font-bold text-gray-600 mb-1.5">Imagen del anuncio</label>

    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-accent/40 transition cursor-pointer"
         @click="$refs.imgInput.click()">
        <template x-if="preview">
            <img :src="preview" class="h-40 mx-auto rounded-lg object-cover mb-2">
        </template>
        <template x-if="!preview">
            <div>
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-xs text-gray-400">Clic para seleccionar imagen</p>
                <p class="text-[10px] text-gray-300 mt-0.5">JPG, PNG, GIF — máx. 3 MB</p>
            </div>
        </template>
        <input type="file" name="imagen" accept="image/*" class="hidden" x-ref="imgInput" @change="onChange">
    </div>

    @if($editing && $anuncio->imagenUrl())
        <label class="flex items-center gap-2 mt-2 text-xs text-gray-500 cursor-pointer">
            <input type="checkbox" name="eliminar_imagen" value="1" class="rounded">
            Eliminar imagen actual
        </label>
    @endif
    @error('imagen')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>

{{-- Link --}}
<div class="border border-gray-100 rounded-2xl p-4 bg-gray-50 space-y-3">
    <p class="text-xs font-bold text-gray-600">Enlace (opcional)</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Tipo de enlace</label>
            <select name="tipo_link"
                    class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none bg-white">
                <option value="">Sin enlace</option>
                <option value="whatsapp" @selected(old('tipo_link', $anuncio?->tipo_link) === 'whatsapp')>WhatsApp</option>
                <option value="externo"  @selected(old('tipo_link', $anuncio?->tipo_link) === 'externo')>Enlace externo</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Texto del botón</label>
            <input type="text" name="link_texto" maxlength="60"
                   value="{{ old('link_texto', $anuncio?->link_texto ?? 'Ver más') }}"
                   placeholder="Ver más"
                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none">
        </div>
    </div>

    <div>
        <label class="block text-xs text-gray-500 mb-1">URL del enlace</label>
        <input type="text" name="link_url" maxlength="500"
               value="{{ old('link_url', $anuncio?->link_url) }}"
               placeholder="https://wa.me/51999000000 o https://ejemplo.com"
               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none font-mono text-xs">
        <p class="text-[10px] text-gray-400 mt-1">Para WhatsApp: <span class="font-mono">https://wa.me/51XXXXXXXXX</span></p>
    </div>
    @error('link_url')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>

{{-- Destinatarios --}}
<div>
    <label class="block text-xs font-bold text-gray-600 mb-2">Visible para</label>
    <div class="flex flex-wrap gap-3">
        @foreach($destinos as $valor => $etiqueta)
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="destinatarios[]" value="{{ $valor }}"
                       @checked(in_array($valor, $selDest))
                       class="w-4 h-4 rounded text-accent focus:ring-accent/30">
                <span class="text-sm text-gray-600 group-hover:text-gray-800 transition">{{ $etiqueta }}</span>
            </label>
        @endforeach
    </div>
    @error('destinatarios')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>

{{-- Vigencia --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-xs font-bold text-gray-600 mb-1.5">Fecha de inicio <span class="text-gray-400 font-normal">(opcional)</span></label>
        <input type="date" name="fecha_inicio"
               value="{{ old('fecha_inicio', $anuncio?->fecha_inicio?->format('Y-m-d')) }}"
               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition">
        @error('fecha_inicio')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs font-bold text-gray-600 mb-1.5">Fecha de fin <span class="text-gray-400 font-normal">(opcional)</span></label>
        <input type="date" name="fecha_fin"
               value="{{ old('fecha_fin', $anuncio?->fecha_fin?->format('Y-m-d')) }}"
               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition">
        @error('fecha_fin')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Orden + Activo --}}
<div class="flex items-center gap-6 flex-wrap">
    <div>
        <label class="block text-xs font-bold text-gray-600 mb-1.5">Orden de aparición</label>
        <input type="number" name="orden" min="0" max="999"
               value="{{ old('orden', $anuncio?->orden ?? $siguienteOrden) }}"
               class="w-24 px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-accent/30 focus:border-accent outline-none transition text-center">
        <p class="text-[10px] text-gray-400 mt-1">Menor número = aparece primero</p>
        @error('orden')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center gap-2 pt-4">
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1"
                   @checked(old('activo', $anuncio ? $anuncio->activo : true))
                   class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-accent/30
                        rounded-full peer peer-checked:after:translate-x-full after:content-['']
                        after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full
                        after:h-5 after:w-5 after:transition-all peer-checked:bg-accent"></div>
        </label>
        <span class="text-sm font-semibold text-gray-700">Publicar anuncio</span>
    </div>
</div>
