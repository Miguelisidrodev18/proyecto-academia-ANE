@php
$gradients = [
    'pollito'    => 'from-blue-700 via-blue-600 to-blue-400',
    'intermedio' => 'from-[#082B59] via-[#1a5ba0] to-[#30A9D9]',
    'ambos'      => 'from-violet-800 via-violet-600 to-violet-400',
];
$grad = $gradients[$curso->nivel] ?? $gradients['intermedio'];
@endphp

<div class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
            hover:shadow-xl hover:-translate-y-1 transition-all duration-300
            {{ !$curso->activo ? 'opacity-60' : '' }} flex flex-col">

    {{-- Thumbnail con imagen o gradiente --}}
    <div class="relative h-36 bg-gradient-to-br {{ $grad }} overflow-hidden flex-shrink-0">

        @if($curso->imagen_url)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($curso->imagen_url) }}"
                 alt="{{ $curso->nombre }}"
                 class="absolute inset-0 w-full h-full object-cover opacity-60
                        group-hover:opacity-80 group-hover:scale-105 transition-all duration-500">
        @else
            <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-white/5"></div>
            <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full bg-white/5"></div>
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

        {{-- Badges superiores --}}
        <div class="absolute top-2.5 right-2.5 flex flex-col gap-1 items-end">
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide
                         bg-white/15 backdrop-blur-sm text-white/90 border border-white/10">
                {{ $curso->tipoLabel() }}
            </span>
            @if($curso->grado)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                             bg-amber-400/25 backdrop-blur-sm text-amber-200">
                    {{ $curso->gradoLabel() }}
                </span>
            @endif
        </div>

        {{-- Nombre sobre la imagen --}}
        <div class="absolute bottom-0 left-0 right-0 px-4 pb-3">
            <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest mb-0.5">{{ $curso->nivelLabel() }}</p>
            <h2 class="text-base font-black text-white leading-tight drop-shadow-lg pr-16">{{ $curso->nombre }}</h2>
        </div>
    </div>

    {{-- Métricas --}}
    <div class="px-4 py-3 border-b border-gray-50 flex items-center gap-4">
        <span class="text-xs text-gray-500 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="font-semibold text-gray-700">{{ $curso->alumnos_count }}</span> alumnos
        </span>
        <span class="text-xs text-gray-500 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span class="font-semibold text-gray-700">{{ $curso->clases_count }}</span> clases
        </span>
        <span class="text-xs text-gray-500 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="font-semibold text-gray-700">{{ $curso->materiales_count }}</span> materiales
        </span>
    </div>

    {{-- Descripción + estado --}}
    <div class="px-4 py-3 flex-1">
        @if($curso->descripcion)
            <p class="text-gray-500 text-xs leading-relaxed line-clamp-2">{{ $curso->descripcion }}</p>
        @else
            <p class="text-gray-300 text-xs italic">Sin descripción.</p>
        @endif

        <div class="mt-2.5">
            <span @class([
                'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold',
                'bg-emerald-50 text-emerald-700 border border-emerald-200' => $curso->activo,
                'bg-gray-100 text-gray-500 border border-gray-200'         => !$curso->activo,
            ])>
                <span class="w-1.5 h-1.5 rounded-full {{ $curso->activo ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                {{ $curso->activo ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
    </div>

    {{-- Footer --}}
    <div class="px-4 pb-4 pt-0 flex items-center gap-2">
        <a href="{{ route('cursos.show', $curso) }}"
           class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-primary-dark/5 text-primary-dark
                  hover:bg-primary-dark hover:text-white transition-all duration-200">
            Ver detalle
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('cursos.edit', $curso) }}"
           class="flex-1 text-center py-2 rounded-xl text-xs font-bold bg-gray-100 text-gray-600
                  hover:bg-gray-200 transition-all duration-200">
            Editar
        </a>
        <form method="POST" action="{{ route('cursos.toggle', $curso) }}">
            @csrf @method('PATCH')
            <button type="submit" title="{{ $curso->activo ? 'Desactivar' : 'Activar' }}"
                    class="p-2 rounded-xl transition-all duration-200
                           {{ $curso->activo ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}">
                @if($curso->activo)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                @else
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
            </button>
        </form>
        @endif
    </div>
</div>
