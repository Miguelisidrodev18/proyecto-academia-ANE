@php
    use Illuminate\Support\Facades\Storage;
    $editing      = isset($curso);
    $nombre       = old('nombre',      $editing ? $curso->nombre      : '');
    $descripcion  = old('descripcion', $editing ? $curso->descripcion : '');
    $nivel        = old('nivel',       $editing ? $curso->nivel       : '');
    $grado        = old('grado',       $editing ? $curso->grado       : '');
    $tipo         = old('tipo',        $editing ? $curso->tipo        : '');
    $zoom_link    = old('zoom_link',   $editing ? $curso->zoom_link   : '');
    $activo       = old('activo',      $editing ? ($curso->activo ? '1' : '0') : '1');
    $imagenActual = $editing && $curso->imagen_url ? Storage::disk('public')->url($curso->imagen_url) : null;
@endphp

{{-- Sección 1: Información del curso --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4"
     x-data="{
         nivel: '{{ $nivel }}',
         activo: {{ $activo === '1' ? 'true' : 'false' }},
     }">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-primary-dark/10 flex items-center justify-center">
            <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Información del curso</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Nombre --}}
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Nombre del curso <span class="text-red-400">*</span>
            </label>
            <input type="text" name="nombre" value="{{ $nombre }}"
                   placeholder="Ej: Física, Matemática, Química..."
                   class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                          {{ $errors->has('nombre') ? 'border-red-400 bg-red-50 focus:ring-red-200' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            @error('nombre')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nivel --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Nivel <span class="text-red-400">*</span>
            </label>
            <select name="nivel" x-model="nivel"
                    class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                           {{ $errors->has('nivel') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
                <option value="">Seleccionar nivel...</option>
                <option value="pollito"    {{ $nivel === 'pollito'    ? 'selected' : '' }}>Pollito (Escolar)</option>
                <option value="intermedio" {{ $nivel === 'intermedio' ? 'selected' : '' }}>Intermedio (Pre-Universitario)</option>
                <option value="ambos"      {{ $nivel === 'ambos'      ? 'selected' : '' }}>Todos los niveles</option>
            </select>
            @error('nivel')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Grado (solo si nivel = pollito) --}}
        <div x-show="nivel === 'pollito'" x-transition>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Grado
            </label>
            <select name="grado"
                    class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                           border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20">
                <option value="">Sin especificar</option>
                @for($g = 1; $g <= 5; $g++)
                    <option value="{{ $g }}" {{ (int)$grado === $g ? 'selected' : '' }}>{{ $g }}.° grado</option>
                @endfor
            </select>
            @error('grado')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tipo --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Tipo <span class="text-red-400">*</span>
            </label>
            <select name="tipo"
                    class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                           {{ $errors->has('tipo') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
                <option value="">Seleccionar tipo...</option>
                <option value="reforzamiento"    {{ $tipo === 'reforzamiento'    ? 'selected' : '' }}>Reforzamiento</option>
                <option value="preuniversitario" {{ $tipo === 'preuniversitario' ? 'selected' : '' }}>Pre-Universitario</option>
            </select>
            @error('tipo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Descripción
            </label>
            <textarea name="descripcion" rows="3"
                      placeholder="Describe el contenido y objetivos de este curso..."
                      class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none resize-none
                             {{ $errors->has('descripcion') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">{{ $descripcion }}</textarea>
            @error('descripcion')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Días de la semana --}}
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                Días de clase
                <span class="normal-case font-normal text-gray-400 ml-1">(activa el botón Zoom esos días)</span>
            </label>
            @php
                $diasOrden     = \App\Models\Curso::ordenDias();
                $etiquetas     = ['lunes'=>'Lu','martes'=>'Ma','miercoles'=>'Mi','jueves'=>'Ju','viernes'=>'Vi','sabado'=>'Sá','domingo'=>'Do'];
                $diasSeleccionados = old('dias_semana', $editing ? ($curso->dias_semana ?? []) : []);
            @endphp
            <div class="flex flex-wrap gap-2">
                @foreach($diasOrden as $dia)
                    @php $activo = in_array($dia, $diasSeleccionados); @endphp
                    <label class="cursor-pointer select-none">
                        <input type="checkbox" name="dias_semana[]" value="{{ $dia }}"
                               {{ $activo ? 'checked' : '' }}
                               class="sr-only peer">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-xs font-black
                                     border-2 transition-all duration-150
                                     peer-checked:bg-accent peer-checked:text-white peer-checked:border-accent
                                     peer-checked:shadow-md peer-checked:shadow-accent/30
                                     border-gray-200 text-gray-400 hover:border-accent/50 hover:text-accent">
                            {{ $etiquetas[$dia] }}
                        </span>
                    </label>
                @endforeach
            </div>
            @error('dias_semana')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Hora de inicio --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Hora de inicio
            </label>
            <input type="time" name="hora_inicio"
                   value="{{ old('hora_inicio', $editing ? ($curso->hora_inicio ? \Carbon\Carbon::parse($curso->hora_inicio)->format('H:i') : '') : '') }}"
                   class="w-full px-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                          {{ $errors->has('hora_inicio') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            @error('hora_inicio')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Link de Zoom (único para el curso) --}}
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                Link de Zoom
                <span class="normal-case font-normal text-gray-400 ml-1">(único para todas las clases)</span>
            </label>
            <div class="relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input type="url" name="zoom_link" value="{{ $zoom_link }}"
                       placeholder="https://zoom.us/j/..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm transition-all outline-none
                              {{ $errors->has('zoom_link') ? 'border-red-400 bg-red-50 focus:ring-red-200' : 'border-gray-200 bg-gray-50 focus:bg-white focus:border-accent focus:ring-2 focus:ring-accent/20' }}">
            </div>
            @error('zoom_link')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Toggle Activo --}}
        <div class="sm:col-span-2">
            <input type="hidden" name="activo" :value="activo ? '1' : '0'">
            <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition-colors cursor-pointer"
                 @click="activo = !activo">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                         :class="activo ? 'bg-emerald-100' : 'bg-gray-100'">
                        <svg class="w-4 h-4 transition-colors" :class="activo ? 'text-emerald-600' : 'text-gray-400'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Curso activo</p>
                        <p class="text-xs text-gray-400">Los cursos activos son visibles para los alumnos</p>
                    </div>
                </div>
                <div class="relative flex-shrink-0">
                    <div class="w-12 h-6 rounded-full transition-colors duration-200"
                         :class="activo ? 'bg-emerald-500' : 'bg-gray-200'"></div>
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200"
                         :class="activo ? 'translate-x-6' : 'translate-x-0'"></div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Sección 2: Imagen de portada --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4"
     x-data="{
         preview: '{{ $imagenActual ?? '' }}',
         handleFile(e) {
             const file = e.target.files[0];
             if (!file) return;
             const reader = new FileReader();
             reader.onload = ev => this.preview = ev.target.result;
             reader.readAsDataURL(file);
         }
     }">
    <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-primary-dark/10 flex items-center justify-center">
            <svg class="w-4 h-4 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-gray-700">Imagen de portada</h3>
    </div>
    <div class="p-5">
        <div class="flex flex-col sm:flex-row gap-5 items-start">

            {{-- Preview --}}
            <div class="flex-shrink-0">
                <div class="w-40 h-28 rounded-xl border-2 border-dashed border-gray-200 overflow-hidden bg-gray-50 flex items-center justify-center">
                    <template x-if="preview">
                        <img :src="preview" alt="Preview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!preview">
                        <div class="text-center p-3">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs text-gray-400">Sin imagen</p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Uploader --}}
            <div class="flex-1 min-w-0">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                    Subir imagen
                </label>
                <label class="flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-gray-200
                              hover:border-accent hover:bg-accent/5 transition-all cursor-pointer group">
                    <div class="w-9 h-9 rounded-xl bg-gray-100 group-hover:bg-accent/10 flex items-center justify-center flex-shrink-0 transition-colors">
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-600 group-hover:text-primary-dark transition-colors">
                            Seleccionar imagen
                        </p>
                        <p class="text-xs text-gray-400">JPG, PNG, WebP · máx. 2 MB</p>
                    </div>
                    <input type="file" name="imagen" accept="image/*" class="hidden" @change="handleFile($event)">
                </label>
                @error('imagen')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-2">La imagen se muestra como fondo en la tarjeta y perfil del curso.</p>
            </div>
        </div>
    </div>
</div>
