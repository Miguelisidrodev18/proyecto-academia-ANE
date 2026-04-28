<div x-data="{
        open: true,
        current: 0,
        total: {{ $anuncios->count() }},
        vioTodos: {{ $anuncios->count() === 1 ? 'true' : 'false' }},
        cerrar() { this.open = false; },
        next() {
            if (this.current < this.total - 1) {
                this.current++;
                if (this.current === this.total - 1) this.vioTodos = true;
            }
        },
        prev() { if (this.current > 0) this.current--; }
     }"
     x-show="open"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[60] flex items-center justify-center p-4"
     style="background: rgba(0,0,0,0.82); backdrop-filter: blur(5px);">

    <div class="relative w-full max-w-md"
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100">

        {{-- ── Slides ─────────────────────────────────────────── --}}
        <div class="relative rounded-2xl overflow-hidden shadow-2xl bg-black">

            @foreach($anuncios as $anuncio)
            <div x-show="current === {{ $loop->index }}"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="relative">

                {{-- Imagen --}}
                @if($anuncio->imagenUrl())
                    <img src="{{ $anuncio->imagenUrl() }}"
                         alt="{{ $anuncio->titulo }}"
                         class="w-full object-contain"
                         style="max-height: 80vh;">
                @else
                    <div class="flex items-center justify-center px-8 text-center"
                         style="min-height:320px; background:linear-gradient(135deg,#082B59,#30A9D9);">
                        <h2 class="text-white font-black text-2xl leading-tight">{{ $anuncio->titulo }}</h2>
                    </div>
                @endif

                {{-- Gradiente inferior sobre la imagen --}}
                <div class="absolute bottom-0 left-0 right-0 h-28 pointer-events-none"
                     style="background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, transparent 100%);"></div>

                {{-- Botón de link FLOTANTE --}}
                @if($anuncio->link_url)
                <div class="absolute bottom-4 left-4 z-10">
                    @if($anuncio->tipo_link === 'whatsapp')
                        <a href="{{ $anuncio->link_url }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold
                                  bg-[#25D366] hover:bg-[#1ebe5d] text-white shadow-lg
                                  transition-all duration-200 hover:-translate-y-0.5">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            {{ $anuncio->link_texto ?? 'Ver más' }}
                        </a>
                    @else
                        <a href="{{ $anuncio->link_url }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold
                                  bg-white/90 hover:bg-white text-primary-dark shadow-lg
                                  transition-all duration-200 hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            {{ $anuncio->link_texto ?? 'Ver más' }}
                        </a>
                    @endif
                </div>
                @endif

                {{-- Flecha siguiente FLOTANTE (no es el último) --}}
                @if($anuncios->count() > 1)
                <div class="absolute bottom-4 right-4 z-10 flex items-center gap-2">
                    {{-- Contador --}}
                    <span class="text-white/70 text-xs font-bold tabular-nums bg-black/40 px-2 py-1 rounded-lg">
                        {{ $loop->index + 1 }}/{{ $anuncios->count() }}
                    </span>
                    {{-- Siguiente --}}
                    <button x-show="current === {{ $loop->index }} && current < total - 1"
                            @click="next()"
                            class="w-9 h-9 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur-sm
                                   border border-white/30 flex items-center justify-center
                                   text-white transition-all hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
                @endif
            </div>
            @endforeach

            {{-- X: solo visible cuando ya vio todos --}}
            <button x-show="vioTodos"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-75"
                    x-transition:enter-end="opacity-100 scale-100"
                    @click="cerrar()"
                    class="absolute top-3 right-3 z-20 w-9 h-9 rounded-full bg-black/60 hover:bg-black/90
                           flex items-center justify-center text-white transition-all hover:scale-110 shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Dots debajo del modal --}}
        @if($anuncios->count() > 1)
        <div class="flex justify-center gap-2 mt-3">
            @foreach($anuncios as $anuncio)
            <div :class="current === {{ $loop->index }} ? 'w-5 bg-white' : 'w-2 bg-white/30'"
                 class="h-2 rounded-full transition-all duration-300"></div>
            @endforeach
        </div>
        @endif

    </div>
</div>
