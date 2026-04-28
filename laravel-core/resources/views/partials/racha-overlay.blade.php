@php
    $racha    = $rachaInfo['racha_actual'];
    $subio    = $rachaInfo['si_subio_racha'];
    $perdio   = $rachaInfo['si_perdio_racha'];
    $anterior = $rachaInfo['racha_anterior'];

    if ($racha <= 7) {
        $gradStart   = '#1e3a8a'; $gradMid = '#2563eb'; $gradEnd = '#60a5fa';
        $glowColor   = 'rgba(96,165,250,0.5)';
        $badgeBg     = 'rgba(219,234,254,0.15)';
        $badgeBorder = 'rgba(147,197,253,0.35)';
        $accentText  = '#bfdbfe';
        $dimText     = 'rgba(219,234,254,0.75)';
        $emberColor  = '#93c5fd';
    } elseif ($racha <= 30) {
        $gradStart   = '#064e3b'; $gradMid = '#059669'; $gradEnd = '#34d399';
        $glowColor   = 'rgba(52,211,153,0.5)';
        $badgeBg     = 'rgba(209,250,229,0.15)';
        $badgeBorder = 'rgba(110,231,183,0.35)';
        $accentText  = '#a7f3d0';
        $dimText     = 'rgba(209,250,229,0.75)';
        $emberColor  = '#6ee7b7';
    } elseif ($racha <= 50) {
        $gradStart   = '#3b0764'; $gradMid = '#7c3aed'; $gradEnd = '#a78bfa';
        $glowColor   = 'rgba(167,139,250,0.5)';
        $badgeBg     = 'rgba(237,233,254,0.15)';
        $badgeBorder = 'rgba(196,181,253,0.35)';
        $accentText  = '#ddd6fe';
        $dimText     = 'rgba(237,233,254,0.75)';
        $emberColor  = '#c4b5fd';
    } elseif ($racha <= 75) {
        $gradStart   = '#7c2d12'; $gradMid = '#ea580c'; $gradEnd = '#fb923c';
        $glowColor   = 'rgba(251,146,60,0.5)';
        $badgeBg     = 'rgba(255,237,213,0.15)';
        $badgeBorder = 'rgba(253,186,116,0.35)';
        $accentText  = '#fed7aa';
        $dimText     = 'rgba(255,237,213,0.75)';
        $emberColor  = '#fdba74';
    } else {
        $gradStart   = '#713f12'; $gradMid = '#d97706'; $gradEnd = '#fbbf24';
        $glowColor   = 'rgba(251,191,36,0.55)';
        $badgeBg     = 'rgba(254,243,199,0.15)';
        $badgeBorder = 'rgba(252,211,77,0.4)';
        $accentText  = '#fde68a';
        $dimText     = 'rgba(254,243,199,0.75)';
        $emberColor  = '#fcd34d';
    }

    $emoji     = $perdio ? ($anterior > 0 ? '💔' : '🔥') : ($racha >= 75 ? '💎' : '🔥');
    $titulo    = $perdio
        ? ($anterior > 0 ? 'Perdiste tu racha' : '¡Comenzaste tu racha!')
        : ($racha === 1 && $anterior === 0 ? '¡Comenzaste tu racha!' : "Día {$racha} de racha");
    $subtitulo = $perdio
        ? ($anterior > 0 ? "Tenías una racha de {$anterior} días. ¡Empieza de nuevo!" : '¡Regresa mañana para continuar!')
        : ($racha === 1 && $anterior === 0 ? '¡Regresa mañana para continuar!' : '¡Sigue así, vas muy bien!');
    $motivacion = $racha >= 30 ? '¡Eres una máquina de constancia!'
        : ($racha >= 15 ? '¡Tu dedicación es admirable!'
        : ($racha >= 7  ? '¡Una semana de racha, increíble!'
        : ($racha >= 3  ? '¡Vas construyendo el hábito!'
        :                 '¡El primer paso es el más importante!')));

    if ($racha > 1) {
        $metaSig  = $racha <= 7 ? 7 : ($racha <= 30 ? 30 : ($racha <= 50 ? 50 : ($racha <= 75 ? 75 : 100)));
        $metaAnt  = $racha <= 7 ? 0 : ($racha <= 30 ? 7  : ($racha <= 50 ? 30 : ($racha <= 75 ? 50 : 75)));
        $progreso = $metaSig > $metaAnt
            ? min(100, (($racha - $metaAnt) / ($metaSig - $metaAnt)) * 100)
            : 100;
    }

    // Ember particles
    $embers = [];
    for ($i = 0; $i < 22; $i++) {
        $embers[] = [
            'left'    => rand(3, 97),
            'delay'   => round($i * 0.28, 2),
            'dur'     => round(3.2 + ($i % 6) * 0.55, 1),
            'size'    => 3 + ($i % 5),
            'drift'   => ($i % 2 === 0 ? '' : '-') . rand(12, 32),
            'opacity' => round(0.5 + ($i % 4) * 0.12, 2),
        ];
    }
@endphp

<style>
@keyframes rachaEmber {
    0%   { transform: translateY(0) translateX(0) scale(1); opacity: var(--eopacity); }
    35%  { transform: translateY(-38vh) translateX(var(--edrift)) scale(0.7); opacity: calc(var(--eopacity) * 0.65); }
    70%  { transform: translateY(-68vh) translateX(calc(var(--edrift) * -0.4)) scale(0.4); opacity: calc(var(--eopacity) * 0.25); }
    100% { transform: translateY(-96vh) translateX(0) scale(0.05); opacity: 0; }
}
@keyframes rachaFlameSway {
    0%   { transform: rotate(-5deg) scale(1); }
    20%  { transform: rotate(3deg)  scale(1.08); }
    40%  { transform: rotate(-3deg) scale(1.05); }
    60%  { transform: rotate(5deg)  scale(1.12); }
    80%  { transform: rotate(-2deg) scale(1.06); }
    100% { transform: rotate(-5deg) scale(1); }
}
@keyframes rachaGlowPulse {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50%       { opacity: 0.7; transform: scale(1.25); }
}
@keyframes rachaGlowRing {
    0%   { transform: scale(1);   opacity: 0.6; }
    100% { transform: scale(2.2); opacity: 0; }
}
@keyframes rachaSlideUp {
    from { transform: translateY(24px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}
</style>

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
     style="background: linear-gradient(155deg, {{ $gradStart }} 0%, {{ $gradMid }} 55%, {{ $gradEnd }} 100%);">

    {{-- ── Fondo: brasas + glows ──────────────────────────────── --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">

        {{-- Ember particles --}}
        @foreach($embers as $em)
        <div class="absolute rounded-full"
             style="left: {{ $em['left'] }}%;
                    bottom: -8px;
                    width: {{ $em['size'] }}px;
                    height: {{ $em['size'] }}px;
                    background: {{ $emberColor }};
                    box-shadow: 0 0 {{ $em['size'] * 2 }}px {{ $emberColor }};
                    --edrift: {{ $em['drift'] }}px;
                    --eopacity: {{ $em['opacity'] }};
                    animation: rachaEmber {{ $em['dur'] }}s {{ $em['delay'] }}s linear infinite;">
        </div>
        @endforeach

        {{-- Glow blobs animados --}}
        <div class="absolute rounded-full blur-3xl"
             style="top: 10%; left: 15%; width: 420px; height: 420px;
                    background: {{ $glowColor }};
                    animation: rachaGlowPulse 3s ease-in-out infinite;"></div>
        <div class="absolute rounded-full blur-3xl"
             style="bottom: 10%; right: 15%; width: 300px; height: 300px;
                    background: {{ $glowColor }};
                    animation: rachaGlowPulse 2.4s ease-in-out infinite; animation-delay: 1s;"></div>
        <div class="absolute rounded-full blur-2xl"
             style="top: 50%; left: 50%; width: 200px; height: 200px;
                    transform: translate(-50%,-50%);
                    background: {{ $glowColor }};
                    animation: rachaGlowPulse 2s ease-in-out infinite; animation-delay: 0.5s;"></div>

        {{-- Patrón de puntos --}}
        <div class="absolute inset-0 opacity-[0.06]"
             style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0);
                    background-size: 38px 38px;"></div>
    </div>

    {{-- ── Card central ───────────────────────────────────────── --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-700 delay-150"
         x-transition:enter-start="opacity-0 scale-75 -translate-y-10"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         class="relative w-full max-w-sm text-center">

        {{-- Emoji con glow multicapa --}}
        <div class="mb-5 relative inline-block">

            @if(!$perdio)
            {{-- Anillos de glow --}}
            <div class="absolute rounded-full"
                 style="inset: -40px;
                        background: {{ $glowColor }};
                        filter: blur(30px);
                        animation: rachaGlowPulse 1.8s ease-in-out infinite;"></div>
            <div class="absolute rounded-full"
                 style="inset: -20px;
                        background: {{ $glowColor }};
                        filter: blur(14px);
                        animation: rachaGlowPulse 1.2s ease-in-out infinite; animation-delay: 0.4s;"></div>
            {{-- Anillo expansivo --}}
            <div class="absolute inset-0 rounded-full"
                 style="background: {{ $glowColor }};
                        animation: rachaGlowRing 2s ease-out infinite;"></div>
            @endif

            <div class="text-9xl leading-none select-none relative z-10"
                 style="animation: rachaFlameSway 2.2s ease-in-out infinite;
                        filter: drop-shadow(0 0 28px {{ $glowColor }}) drop-shadow(0 0 55px {{ $glowColor }});">
                {{ $emoji }}
            </div>
        </div>

        {{-- Contador animado --}}
        @if(!$perdio)
        <div x-data="{
                n: 0,
                target: {{ $racha }},
            }"
             x-init="$nextTick(() => {
                 const steps = 45;
                 let i = 0;
                 const tmr = setInterval(() => {
                     i++;
                     const t = i / steps;
                     n = Math.round(target * (1 - Math.pow(1 - t, 3)));
                     if (i >= steps) { n = target; clearInterval(tmr); }
                 }, 22);
             })"
             class="inline-flex items-center gap-3 px-6 py-3 rounded-2xl mb-5 border"
             style="background: {{ $badgeBg }};
                    border-color: {{ $badgeBorder }};
                    backdrop-filter: blur(12px);
                    box-shadow: 0 0 30px {{ $glowColor }}, inset 0 1px 0 rgba(255,255,255,0.15);">
            <span class="font-black text-white tabular-nums leading-none"
                  style="font-size: 3.5rem; text-shadow: 0 0 30px rgba(255,255,255,0.5);"
                  x-text="n">0</span>
            <div class="text-left">
                <p class="text-sm font-black uppercase tracking-widest leading-none"
                   style="color: {{ $accentText }};">
                    {{ $racha === 1 ? 'día' : 'días' }}
                </p>
                <p class="text-xs leading-none mt-1 font-medium" style="color: {{ $dimText }};">
                    consecutivos
                </p>
            </div>
        </div>

        @else

        {{-- Perdió racha --}}
        <div class="inline-flex items-center gap-3 px-6 py-3 rounded-2xl mb-5 border"
             style="background: rgba(254,242,242,0.12); border-color: rgba(252,165,165,0.3);
                    backdrop-filter: blur(12px);">
            <span class="font-black text-white tabular-nums leading-none" style="font-size: 3.5rem;">
                {{ $anterior }}
            </span>
            <div class="text-left">
                <p class="text-sm font-black uppercase tracking-widest leading-none text-red-200">
                    {{ $anterior === 1 ? 'día' : 'días' }}
                </p>
                <p class="text-xs leading-none mt-1 font-medium text-red-200/70">perdidos</p>
            </div>
        </div>
        @endif

        {{-- Título --}}
        <h1 class="text-3xl font-black text-white mb-2 leading-tight"
            style="text-shadow: 0 2px 20px rgba(0,0,0,0.4); animation: rachaSlideUp 0.6s ease-out 0.3s both;">
            {{ $titulo }}
        </h1>

        {{-- Subtítulo --}}
        <p class="text-base mb-1.5 leading-relaxed"
           style="color: {{ $dimText }}; animation: rachaSlideUp 0.6s ease-out 0.45s both;">
            {{ $subtitulo }}
        </p>

        {{-- Motivación --}}
        @if(!$perdio)
        <p class="text-sm font-bold mb-7"
           style="color: {{ $accentText }}; animation: rachaSlideUp 0.6s ease-out 0.55s both;">
            {{ $motivacion }}
        </p>
        @else
        <p class="text-sm font-bold mb-7 text-red-200/80"
           style="animation: rachaSlideUp 0.6s ease-out 0.55s both;">
            ¡Vuelve mañana para iniciar una nueva racha!
        </p>
        @endif

        {{-- Barra de nivel animada --}}
        @if(!$perdio && $racha > 1)
        <div class="mb-7 px-1" style="animation: rachaSlideUp 0.6s ease-out 0.6s both;">
            <div class="flex justify-between text-xs mb-2 font-medium" style="color: {{ $dimText }};">
                <span>Hacia el nivel siguiente</span>
                <span class="font-black" style="color: {{ $accentText }};">{{ $racha }}/{{ $metaSig }} días</span>
            </div>
            <div class="h-3 rounded-full overflow-hidden relative"
                 style="background: rgba(255,255,255,0.12); box-shadow: inset 0 1px 3px rgba(0,0,0,0.3);">
                <div x-data="{}"
                     x-init="setTimeout(() => {
                         $el.style.width = '{{ $progreso }}%';
                     }, 900)"
                     class="h-full rounded-full absolute left-0 top-0"
                     style="width: 0%;
                            transition: width 1.8s cubic-bezier(0.34, 1.56, 0.64, 1);
                            background: linear-gradient(to right, {{ $accentText }}, white);
                            box-shadow: 0 0 14px {{ $glowColor }}, 0 0 28px {{ $glowColor }};">
                </div>
                {{-- Shine --}}
                <div class="absolute inset-0 rounded-full opacity-30"
                     style="background: linear-gradient(to bottom, rgba(255,255,255,0.6), transparent);"></div>
            </div>
        </div>
        @else
        <div class="mb-7"></div>
        @endif

        {{-- Botón continuar --}}
        <button @click="open = false"
                class="w-full py-4 px-8 rounded-2xl font-black text-base transition-all duration-200
                       active:scale-95 hover:scale-[1.03]"
                style="background: rgba(255,255,255,0.95);
                       color: {{ $gradMid }};
                       box-shadow: 0 8px 32px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.2);
                       animation: rachaSlideUp 0.6s ease-out 0.7s both;">
            ¡Continuar! →
        </button>

        <p class="mt-4 text-xs font-medium" style="color: {{ $dimText }};">
            @if($perdio)
                Racha nueva: 1 día · ¡Tú puedes lograrlo!
            @else
                Racha actual: {{ $racha }} {{ $racha === 1 ? 'día' : 'días' }} 🔥
            @endif
        </p>
    </div>
</div>
