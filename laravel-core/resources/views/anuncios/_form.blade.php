@php
    $anuncio        ??= null;
    $siguienteOrden ??= 0;
    $planes         ??= collect();
    $editing         = $anuncio !== null;
    $destinos  = ['alumno' => 'Alumnos', 'representante' => 'Representantes'];
    $selDest   = old('destinatarios', $editing ? ($anuncio->destinatarios ?? []) : ['alumno', 'representante']);
    $selPlanes = array_map('intval', old('planes_ids', $editing ? ($anuncio->planes_ids ?? []) : []));
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

{{-- Imágenes: escritorio y móvil --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    {{-- Imagen escritorio --}}
    <div x-data="{
            preview: @js($editing && $anuncio->imagenUrl() ? $anuncio->imagenUrl() : null),
            onChange(e) {
                const file = e.target.files[0];
                if (file) this.preview = URL.createObjectURL(file);
            }
        }">
        <label class="block text-xs font-bold text-gray-600 mb-1.5">
            Imagen escritorio
            <span class="text-gray-400 font-normal ml-1">— horizontal (16:9)</span>
        </label>

        <div class="border-2 border-dashed border-gray-200 rounded-xl p-3 text-center hover:border-accent/40 transition cursor-pointer"
             @click="$refs.imgDesk.click()">
            <template x-if="preview">
                <img :src="preview" class="h-32 w-full mx-auto rounded-lg object-cover mb-1">
            </template>
            <template x-if="!preview">
                <div class="py-4">
                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs text-gray-400">Clic para subir</p>
                    <p class="text-[10px] text-gray-300 mt-0.5">Recomendado: 1280×720 px · máx. 5 MB</p>
                </div>
            </template>
            <input type="file" name="imagen" accept="image/*" class="hidden" x-ref="imgDesk" @change="onChange">
        </div>

        @if($editing && $anuncio->imagenUrl())
            <label class="flex items-center gap-2 mt-1.5 text-xs text-gray-500 cursor-pointer">
                <input type="checkbox" name="eliminar_imagen" value="1" class="rounded">
                Eliminar imagen escritorio
            </label>
        @endif
        @error('imagen')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Imagen móvil --}}
    <div x-data="{
            preview: @js($editing && $anuncio->imagenMovilUrl() ? $anuncio->imagenMovilUrl() : null),
            onChange(e) {
                const file = e.target.files[0];
                if (file) this.preview = URL.createObjectURL(file);
            }
        }">
        <label class="block text-xs font-bold text-gray-600 mb-1.5">
            Imagen móvil
            <span class="text-gray-400 font-normal ml-1">— vertical (9:16)</span>
        </label>

        <div class="border-2 border-dashed border-blue-200 rounded-xl p-3 text-center hover:border-accent/40 transition cursor-pointer"
             @click="$refs.imgMov.click()">
            <template x-if="preview">
                <img :src="preview" class="h-32 w-full mx-auto rounded-lg object-cover mb-1">
            </template>
            <template x-if="!preview">
                <div class="py-4">
                    <svg class="w-8 h-8 text-blue-200 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs text-gray-400">Clic para subir</p>
                    <p class="text-[10px] text-gray-300 mt-0.5">Recomendado: 720×1280 px · máx. 5 MB</p>
                    <p class="text-[10px] text-blue-400 mt-0.5 font-medium">Sin imagen → usa la de escritorio</p>
                </div>
            </template>
            <input type="file" name="imagen_movil" accept="image/*" class="hidden" x-ref="imgMov" @change="onChange">
        </div>

        @if($editing && $anuncio->imagenMovilUrl())
            <label class="flex items-center gap-2 mt-1.5 text-xs text-gray-500 cursor-pointer">
                <input type="checkbox" name="eliminar_imagen_movil" value="1" class="rounded">
                Eliminar imagen móvil
            </label>
        @endif
        @error('imagen_movil')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

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
    <label class="block text-xs font-bold text-gray-600 mb-2">Visible para (roles)</label>
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

{{-- Planes --}}
@if($planes->isNotEmpty())
<div class="border border-gray-100 rounded-2xl p-4 bg-gray-50 space-y-3">
    <div>
        <p class="text-xs font-bold text-gray-600">Dirigido a planes</p>
        <p class="text-[10px] text-gray-400 mt-0.5">Sin selección = visible para todos los planes de alumnos</p>
    </div>
    <div class="flex flex-wrap gap-3">
        @foreach($planes as $plan)
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="planes_ids[]" value="{{ $plan->id }}"
                       @checked(in_array($plan->id, $selPlanes))
                       class="w-4 h-4 rounded text-accent focus:ring-accent/30">
                <span class="text-sm text-gray-700 group-hover:text-gray-900 transition font-medium">
                    {{ $plan->tipoIcono() }} {{ $plan->nombre }}
                </span>
            </label>
        @endforeach
    </div>
    @error('planes_ids')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>
@endif

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
