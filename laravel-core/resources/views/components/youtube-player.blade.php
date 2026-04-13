@props(['url', 'label' => 'Ver video'])

@php
    $ytId = null;
    if ($url && preg_match(
        '/(?:youtube\.com\/watch\?[^#]*v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
        $url,
        $m
    )) {
        $ytId = $m[1];
    }
    $embedUrl = $ytId ? "https://www.youtube.com/embed/{$ytId}?rel=0" : null;
@endphp

@if($embedUrl)
{{-- ── YouTube: reproduce en modal inline ── --}}
<div x-data="{ open: false }" class="inline-block">

    {{-- Trigger: lo que se pasa como slot --}}
    <div @click="open = true" class="cursor-pointer">
        {{ $slot }}
    </div>

    {{-- Overlay / Modal --}}
    <template x-teleport="body">
        <div x-show="open"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="open = false"
             @click.self="open = false"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm">

            <div class="relative w-full max-w-3xl rounded-2xl overflow-hidden shadow-2xl bg-black"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                {{-- Barra superior --}}
                <div class="flex items-center justify-between px-4 py-2.5 bg-primary-dark">
                    <span class="text-white text-xs font-bold truncate pr-4">{{ $label }}</span>
                    <button @click="open = false"
                            type="button"
                            class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center
                                   text-white/60 hover:text-white hover:bg-white/15 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Iframe 16:9 — src vacío cuando cerrado para detener el video --}}
                <div class="aspect-video">
                    <iframe
                        :src="open ? '{{ $embedUrl }}&autoplay=1' : ''"
                        class="w-full h-full"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </template>
</div>

@else
{{-- ── Fallback: URL que no es YouTube → abre en nueva pestaña ── --}}
<a href="{{ $url }}" target="_blank" rel="noopener noreferrer">
    {{ $slot }}
</a>
@endif
