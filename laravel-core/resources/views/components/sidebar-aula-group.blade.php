{{--
    Grupo "Aula Virtual" del sidebar.
    Props del padre:
      $aulaVirtualActiva          bool
      $aulaVirtualItemsFiltrados  array
      $ocultarTrigger             bool (opcional) — cuando el rol ya tiene la etiqueta afuera
--}}
<div x-data="{ open: {{ $aulaVirtualActiva ? 'true' : 'false' }} }" class="space-y-0.5">

    {{-- Trigger --}}
    <button type="button"
            @click="open = !open"
            :class="open
                ? 'bg-accent/15 text-accent'
                : 'text-white/60 hover:bg-white/10 hover:text-white'"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                   transition-all duration-150 group">

        <div :class="open ? 'bg-accent/20' : 'bg-white/5 group-hover:bg-white/10'"
             class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200">
            <svg style="width:1.125rem;height:1.125rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 :class="open ? 'text-accent' : 'text-white/50 group-hover:text-white/80'">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <span class="flex-1 text-left truncate">Aula Virtual</span>

        <svg class="w-3.5 h-3.5 flex-shrink-0 transition-transform duration-200"
             :class="open ? 'rotate-180 text-accent' : 'text-white/20'"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Sub-items --}}
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="space-y-0.5 pl-2">

        {{-- Línea vertical --}}
        <div class="relative pl-5 border-l border-white/8 ml-3.5 space-y-0.5">
            @foreach($aulaVirtualItemsFiltrados as $sub)
                @php
                    $subActivo = request()->routeIs($sub['route'])
                        || (isset($sub['pattern']) && request()->routeIs($sub['pattern']));
                @endphp

                @if($sub['available'])
                    <a href="{{ route($sub['route']) }}"
                       @class([
                           'flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium transition-all duration-150 group',
                           'bg-accent/15 text-accent'                              => $subActivo,
                           'text-white/55 hover:bg-white/10 hover:text-white/90'   => !$subActivo,
                       ])>
                        <span @class([
                                  'w-1.5 h-1.5 rounded-full flex-shrink-0 transition-colors',
                                  'bg-accent'                   => $subActivo,
                                  'bg-white/20 group-hover:bg-white/50' => !$subActivo,
                              ])></span>
                        <svg style="width:1rem;height:1rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             class="{{ $subActivo ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sub['icon'] }}"/>
                        </svg>
                        <span class="flex-1 truncate">{{ $sub['label'] }}</span>
                    </a>
                @else
                    <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium
                                opacity-40 cursor-not-allowed select-none">
                        <span class="w-1.5 h-1.5 rounded-full bg-white/15 flex-shrink-0"></span>
                        <span class="flex-1 truncate text-white/40">{{ $sub['label'] }}</span>
                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full
                                     bg-white/8 text-white/30 uppercase tracking-wide">Pronto</span>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

</div>
