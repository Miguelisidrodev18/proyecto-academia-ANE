{{--
    Grupo desplegable "Aula Virtual" del sidebar.
    Variables disponibles del padre:
      $aulaVirtualActiva          bool   — si algún sub-item está activo (auto-expande)
      $aulaVirtualItemsFiltrados  array  — sub-items ya filtrados por rol
--}}

<div x-data="{ open: {{ $aulaVirtualActiva ? 'true' : 'false' }} }" class="space-y-0.5">

    {{-- Trigger del grupo --}}
    <button type="button"
            @click="open = !open"
            :class="open
                ? 'bg-[#0BC4D9]/10 text-[#0BC4D9] border-l-2 border-[#0BC4D9]/50'
                : 'text-white/70 hover:bg-white/5 hover:text-white border-l-2 border-transparent'"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                   transition-all duration-150 group">

        {{-- Icono del grupo --}}
        <svg class="w-5 h-5 flex-shrink-0 transition-colors"
             :class="open ? 'text-[#0BC4D9]' : 'text-white/50 group-hover:text-white/80'"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>

        <span class="flex-1 text-left truncate">Aula Virtual</span>

        {{-- Chevron animado --}}
        <svg class="w-3.5 h-3.5 flex-shrink-0 transition-transform duration-200"
             :class="open ? 'rotate-180 text-[#0BC4D9]' : 'text-white/30'"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Sub-items desplegables --}}
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="space-y-0.5 pl-3">

        {{-- Línea vertical de referencia --}}
        <div class="relative">
            <div class="absolute left-0 top-0 bottom-0 w-px bg-white/10 ml-2.5"></div>

            <div class="space-y-0.5 pl-5">
                @foreach($aulaVirtualItemsFiltrados as $sub)
                @php
                    $subActivo = request()->routeIs($sub['route'])
                        || (isset($sub['pattern']) && request()->routeIs($sub['pattern']));
                @endphp
                <a href="{{ route($sub['route']) }}"
                   @class([
                       'flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium transition-all duration-150 group relative',
                       'bg-[#0BC4D9]/15 text-[#0BC4D9]'                                    => $subActivo,
                       'text-white/60 hover:bg-white/5 hover:text-white/90'                 => !$subActivo,
                   ])>

                    {{-- Punto indicador --}}
                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 transition-colors
                                 {{ $subActivo ? 'bg-[#0BC4D9]' : 'bg-white/20 group-hover:bg-white/50' }}"></span>

                    <svg class="w-4 h-4 flex-shrink-0 {{ $subActivo ? 'text-[#0BC4D9]' : 'text-white/40 group-hover:text-white/70' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $sub['icon'] !!}
                    </svg>

                    <span class="flex-1 truncate">{{ $sub['label'] }}</span>

                    @if(!$sub['available'])
                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full
                                     bg-white/10 text-white/40 uppercase tracking-wide flex-shrink-0">
                            Pronto
                        </span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>

</div>
