@if($anuncios->isNotEmpty())
<section class="mb-8">
    <div class="flex items-center gap-2 mb-4">
        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-accent to-secondary flex items-center justify-center flex-shrink-0">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
        </div>
        <h2 class="text-base font-black text-gray-800">Anuncios</h2>
        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-accent/10 text-accent">
            {{ $anuncios->count() }}
        </span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($anuncios as $anuncio)
            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                        hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">

                {{-- Imagen --}}
                @if($anuncio->imagenUrl())
                    <div class="relative h-40 overflow-hidden bg-gray-100">
                        <img src="{{ $anuncio->imagenUrl() }}"
                             alt="{{ $anuncio->titulo }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                @else
                    <div class="h-2 bg-gradient-to-r from-accent to-secondary"></div>
                @endif

                {{-- Contenido --}}
                <div class="p-4">
                    @if($anuncio->titulo)
                        <h3 class="font-black text-gray-800 text-sm leading-tight mb-1.5">{{ $anuncio->titulo }}</h3>
                    @endif

                    @if($anuncio->descripcion)
                        <p class="text-gray-500 text-xs leading-relaxed line-clamp-3">{{ $anuncio->descripcion }}</p>
                    @endif

                    {{-- Botón de enlace --}}
                    @if($anuncio->link_url)
                        @php
                            $btnBase = 'inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200';
                        @endphp

                        @if($anuncio->tipo_link === 'whatsapp')
                            <a href="{{ $anuncio->link_url }}"
                               target="_blank" rel="noopener"
                               class="{{ $btnBase }} bg-green-500 hover:bg-green-600 text-white shadow-sm hover:shadow-md">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                {{ $anuncio->link_texto ?? 'Escribir por WhatsApp' }}
                            </a>
                        @else
                            <a href="{{ $anuncio->link_url }}"
                               target="_blank" rel="noopener"
                               class="{{ $btnBase }} bg-gradient-to-r from-primary-dark to-primary-light hover:from-accent hover:to-secondary text-white shadow-sm hover:shadow-md">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    </div>
</section>
@endif
