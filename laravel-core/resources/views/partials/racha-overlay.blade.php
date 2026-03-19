@php
    $racha = $rachaInfo['racha_actual'];
    $subio = $rachaInfo['si_subio_racha'];
    $perdio = $rachaInfo['si_perdio_racha'];
    $anterior = $rachaInfo['racha_anterior'];

    if ($racha <= 7) {
        $gradStart  = '#1d4ed8';
        $gradMid    = '#2563eb';
        $gradEnd    = '#3b82f6';
        $glowColor  = 'rgba(59,130,246,0.4)';
        $badgeBg    = 'rgba(219,234,254,0.15)';
        $badgeBorder= 'rgba(147,197,253,0.3)';
        $accentText = '#93c5fd';
        $dimText    = 'rgba(219,234,254,0.7)';
    } elseif ($racha <= 30) {
        $gradStart  = '#065f46';
        $gradMid    = '#059669';
        $gradEnd    = '#10b981';
        $glowColor  = 'rgba(16,185,129,0.4)';
        $badgeBg    = 'rgba(209,250,229,0.15)';
        $badgeBorder= 'rgba(110,231,183,0.3)';
        $accentText = '#6ee7b7';
        $dimText    = 'rgba(209,250,229,0.7)';
    } elseif ($racha <= 50) {
        $gradStart  = '#4c1d95';
        $gradMid    = '#7c3aed';
        $gradEnd    = '#8b5cf6';
        $glowColor  = 'rgba(139,92,246,0.4)';
        $badgeBg    = 'rgba(237,233,254,0.15)';
        $badgeBorder= 'rgba(196,181,253,0.3)';
        $accentText = '#c4b5fd';
        $dimText    = 'rgba(237,233,254,0.7)';
    } elseif ($racha <= 75) {
        $gradStart  = '#7c2d12';
        $gradMid    = '#ea580c';
        $gradEnd    = '#f97316';
        $glowColor  = 'rgba(249,115,22,0.4)';
        $badgeBg    = 'rgba(255,237,213,0.15)';
        $badgeBorder= 'rgba(253,186,116,0.3)';
        $accentText = '#fdba74';
        $dimText    = 'rgba(255,237,213,0.7)';
    } else {
        $gradStart  = '#78350f';
        $gradMid    = '#d97706';
        $gradEnd    = '#fbbf24';
        $glowColor  = 'rgba(251,191,36,0.5)';
        $badgeBg    = 'rgba(254,243,199,0.15)';
        $badgeBorder= 'rgba(252,211,77,0.4)';
        $accentText = '#fde68a';
        $dimText    = 'rgba(254,243,199,0.7)';
    }

    $emoji        = $perdio ? '💔' : ($racha >= 75 ? '💎🔥' : '🔥');
    $titulo       = $perdio
        ? 'Perdiste tu racha'
        : ($racha === 1 && $anterior === 0 ? '¡Comenzaste tu racha!' : "Día {$racha} de racha");
    $subtitulo    = $perdio
        ? ($anterior > 0 ? "Tenías una racha de {$anterior} días. ¡Empieza de nuevo!" : '¡Empieza de nuevo!')
        : ($racha === 1 && $anterior === 0 ? '¡Bienvenido! Regresa mañana para continuar.' : '¡Sigue así, vas muy bien!');

    if ($racha >= 30) {
        $motivacion = '¡Eres una máquina de constancia!';
    } elseif ($racha >= 15) {
        $motivacion = '¡Tu dedicación es admirable!';
    } elseif ($racha >= 7) {
        $motivacion = '¡Una semana de racha, increíble!';
    } elseif ($racha >= 3) {
        $motivacion = '¡Vas construyendo el hábito!';
    } else {
        $motivacion = '¡El primer paso es el más importante!';
    }
@endphp

<div x-data="{ open: true }"
     x-show="open"
     x-cloak
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background: linear-gradient(135deg, {{ $gradStart }} 0%, {{ $gradMid }} 50%, {{ $gradEnd }} 100%);">

    {{-- Fondo de partículas animadas --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full opacity-20 blur-3xl animate-pulse"
             style="background: {{ $glowColor }};"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full opacity-15 blur-3xl animate-pulse"
             style="background: {{ $glowColor }}; animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-0 w-48 h-48 rounded-full opacity-10 blur-2xl animate-pulse"
             style="background: {{ $glowColor }}; animation-delay: 0.5s;"></div>
        {{-- Patrón de puntos --}}
        <div class="absolute inset-0 opacity-5"
             style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0);
                    background-size: 40px 40px;"></div>
    </div>

    {{-- Card central --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-700 delay-100"
         x-transition:enter-start="opacity-0 scale-75 -translate-y-8"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         class="relative w-full max-w-sm text-center">

        {{-- Emoji principal con animación --}}
        <div class="mb-6 relative inline-block">
            <div class="text-8xl leading-none select-none"
                 style="filter: drop-shadow(0 0 30px {{ $glowColor }});">
                {{ $emoji }}
            </div>
            @if(!$perdio)
            {{-- Anillo pulsante --}}
            <div class="absolute inset-0 rounded-full animate-ping opacity-20"
                 style="background: {{ $glowColor }}; animation-duration: 2s;"></div>
            @endif
        </div>

        {{-- Contador de racha --}}
        @if(!$perdio)
        <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full mb-4 border"
             style="background: {{ $badgeBg }}; border-color: {{ $badgeBorder }};">
            <span class="text-4xl font-black text-white tabular-nums">{{ $racha }}</span>
            <div class="text-left">
                <p class="text-xs font-bold uppercase tracking-widest leading-none"
                   style="color: {{ $accentText }};">
                    {{ $racha === 1 ? 'día' : 'días' }}
                </p>
                <p class="text-xs leading-none mt-0.5" style="color: {{ $dimText }};">
                    consecutivos
                </p>
            </div>
        </div>
        @else
        <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full mb-4 border"
             style="background: rgba(254,242,242,0.15); border-color: rgba(252,165,165,0.3);">
            <span class="text-4xl font-black text-white tabular-nums">{{ $anterior }}</span>
            <div class="text-left">
                <p class="text-xs font-bold uppercase tracking-widest leading-none text-red-200">
                    {{ $anterior === 1 ? 'día' : 'días' }}
                </p>
                <p class="text-xs leading-none mt-0.5 text-red-200/70">perdidos</p>
            </div>
        </div>
        @endif

        {{-- Título --}}
        <h1 class="text-3xl font-black text-white mb-2 leading-tight">
            {{ $titulo }}
        </h1>

        {{-- Subtítulo --}}
        <p class="text-base mb-2 leading-relaxed" style="color: {{ $dimText }};">
            {{ $subtitulo }}
        </p>

        {{-- Motivación --}}
        @if(!$perdio)
        <p class="text-sm font-semibold mb-8" style="color: {{ $accentText }};">
            {{ $motivacion }}
        </p>
        @else
        <p class="text-sm font-semibold mb-8 text-red-200/80">
            ¡Vuelve mañana para iniciar una nueva racha!
        </p>
        @endif

        {{-- Barra de nivel (solo si no perdió) --}}
        @if(!$perdio && $racha > 1)
        @php
            $metaSiguiente = $racha <= 7 ? 7 : ($racha <= 30 ? 30 : ($racha <= 50 ? 50 : ($racha <= 75 ? 75 : 100)));
            $metaAnterior  = $racha <= 7 ? 0 : ($racha <= 30 ? 7 : ($racha <= 50 ? 30 : ($racha <= 75 ? 50 : 75)));
            $progreso      = $metaSiguiente > $metaAnterior
                ? min(100, (($racha - $metaAnterior) / ($metaSiguiente - $metaAnterior)) * 100)
                : 100;
        @endphp
        <div class="mb-8 px-2">
            <div class="flex justify-between text-xs mb-1.5" style="color: {{ $dimText }};">
                <span>Nivel actual</span>
                <span>{{ $racha }}/{{ $metaSiguiente }} días</span>
            </div>
            <div class="h-2 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.15);">
                <div class="h-full rounded-full transition-all duration-1000"
                     style="width: {{ $progreso }}%; background: {{ $accentText }};"></div>
            </div>
        </div>
        @elseif(!$perdio)
        <div class="mb-8"></div>
        @else
        <div class="mb-8"></div>
        @endif

        {{-- Botón continuar --}}
        <button @click="open = false"
                class="w-full py-4 px-8 rounded-2xl font-black text-base transition-all duration-200
                       active:scale-95 hover:scale-105 hover:shadow-2xl"
                style="background: rgba(255,255,255,0.95);
                       color: {{ $gradMid }};
                       box-shadow: 0 8px 32px rgba(0,0,0,0.3);">
            ¡Continuar! →
        </button>

        {{-- Mensaje motivacional pequeño --}}
        <p class="mt-4 text-xs" style="color: {{ $dimText }};">
            @if($perdio)
                Racha nueva: 1 día · ¡Tú puedes lograrlo!
            @else
                Racha actual: {{ $racha }} {{ $racha === 1 ? 'día' : 'días' }} 🔥
            @endif
        </p>
    </div>
</div>
