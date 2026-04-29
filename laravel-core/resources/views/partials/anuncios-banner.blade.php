@if($anuncios->isNotEmpty())
<section class="mb-8"
    x-data="{
        current: 0,
        total: {{ $anuncios->count() }},
        animating: false,
        prog: 0,
        ticker: null,
        advance() {
            if (this.animating) return;
            this.animating = true;
            this.prog = 0;
            this.current = this.current < this.total - 1 ? this.current + 1 : 0;
            setTimeout(() => this.animating = false, 500);
        },
        go(idx) {
            if (this.animating || idx === this.current) return;
            this.animating = true;
            this.prog = 0;
            this.current = idx;
            setTimeout(() => this.animating = false, 500);
            this.startAuto();
        },
        prev() { this.go(this.current > 0 ? this.current - 1 : this.total - 1); },
        next() { this.go(this.current < this.total - 1 ? this.current + 1 : 0); },
        startAuto() {
            if (this.total <= 1) return;
            clearInterval(this.ticker);
            this.prog = 0;
            this.ticker = setInterval(() => {
                this.prog = Math.min(this.prog + 2, 100);
                if (this.prog >= 100) this.advance();
            }, 100);
        },
        stopAuto() { clearInterval(this.ticker); },
    }"
    x-init="startAuto()"
    @mouseenter="stopAuto()"
    @mouseleave="startAuto()">

    {{-- ── Header ──────────────────────────────────────────────── --}}
    <div class="flex items-center gap-2 mb-3">
        <div class="w-1 h-5 rounded-full bg-gradient-to-b from-accent to-secondary"></div>
        <h2 class="text-base font-black text-gray-800">Anuncios</h2>
        @if($anuncios->count() > 1)
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-accent/10 text-accent">
                {{ $anuncios->count() }}
            </span>
        @endif
    </div>

    {{-- ── Stage ───────────────────────────────────────────────── --}}
    <div class="relative group">

        {{-- Carrusel --}}
        <div class="relative overflow-hidden rounded-2xl shadow-lg"
             style="height: calc(100svh - 180px); min-height: 400px;">

            {{-- Barras de progreso estilo stories --}}
            @if($anuncios->count() > 1)
            <div class="absolute top-0 left-0 right-0 z-20 flex gap-1 px-3 pt-2.5">
                @foreach($anuncios as $anuncio)
                <div class="flex-1 h-[3px] rounded-full bg-white/30 overflow-hidden">
                    <div class="h-full bg-white rounded-full"
                         :style="current === {{ $loop->index }}
                                 ? 'width: ' + prog + '%'
                                 : (current > {{ $loop->index }} ? 'width: 100%' : 'width: 0%')">
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @foreach($anuncios as $anuncio)
            <div x-show="current === {{ $loop->index }}"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 scale-[1.02]"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-[0.98]"
                 class="absolute inset-0">

                {{-- Imagen de fondo --}}
                @if($anuncio->imagenUrl())
                    <img src="{{ $anuncio->imagenUrl() }}"
                         alt="{{ $anuncio->titulo }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full"
                         style="background: linear-gradient(135deg, #082B59 0%, #0f3d7a 50%, #30A9D9 100%);"></div>
                @endif

                {{-- Gradiente sobre la imagen --}}
                <div class="absolute inset-0"
                     style="background: linear-gradient(
                         to right,
                         rgba(5,15,35,0.85) 0%,
                         rgba(5,15,35,0.5) 45%,
                         rgba(5,15,35,0.1) 100%
                     );"></div>
                <div class="absolute inset-0"
                     style="background: linear-gradient(to top, rgba(5,15,35,0.6) 0%, transparent 50%);"></div>

                {{-- Contenido --}}
                <div class="absolute inset-0 flex flex-col justify-end p-6 md:p-8 md:max-w-xl">
                    @if($anuncio->titulo)
                        <h3 class="text-white font-black text-xl md:text-2xl leading-tight mb-2 drop-shadow-lg">
                            {{ $anuncio->titulo }}
                        </h3>
                    @endif
                    @if($anuncio->descripcion)
                        <p class="text-white/75 text-sm leading-relaxed mb-4 line-clamp-2 drop-shadow">
                            {{ $anuncio->descripcion }}
                        </p>
                    @endif

                    @if($anuncio->link_url)
                        @if($anuncio->tipo_link === 'whatsapp')
                            <a href="{{ $anuncio->link_url }}" target="_blank" rel="noopener"
                               class="self-start inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold
                                      bg-[#25D366] hover:bg-[#1ebe5d] text-white shadow-lg
                                      transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                {{ $anuncio->link_texto ?? 'Escribir por WhatsApp' }}
                            </a>
                        @else
                            <a href="{{ $anuncio->link_url }}" target="_blank" rel="noopener"
                               class="self-start inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold
                                      bg-white/90 hover:bg-white text-primary-dark shadow-lg
                                      transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                {{ $anuncio->link_texto ?? 'Ver más' }}
                            </a>
                        @endif
                    @endif
                </div>
            </div>
            @endforeach

            {{-- Flechas (solo si hay más de uno) --}}
            @if($anuncios->count() > 1)
                <button @click="prev()"
                        class="absolute left-3 top-1/2 -translate-y-1/2
                               w-10 h-10 rounded-full bg-black/40 hover:bg-black/70 backdrop-blur-sm
                               border border-white/20 flex items-center justify-center
                               text-white transition-all duration-200
                               opacity-0 group-hover:opacity-100 hover:scale-110 z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button @click="next()"
                        class="absolute right-3 top-1/2 -translate-y-1/2
                               w-10 h-10 rounded-full bg-black/40 hover:bg-black/70 backdrop-blur-sm
                               border border-white/20 flex items-center justify-center
                               text-white transition-all duration-200
                               opacity-0 group-hover:opacity-100 hover:scale-110 z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @endif
        </div>

        {{-- ── Dots ─────────────────────────────────────────────── --}}
        @if($anuncios->count() > 1)
        <div class="flex justify-center gap-1.5 mt-3">
            @foreach($anuncios as $i => $anuncio)
                <button @click="go({{ $loop->index }})"
                        :class="current === {{ $loop->index }}
                            ? 'w-6 bg-primary-dark'
                            : 'w-2 bg-gray-300 hover:bg-gray-400'"
                        class="h-2 rounded-full transition-all duration-300">
                </button>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
