{{--
    Template compartido para módulos no implementados.
    Variables esperadas: $modulo (string), $descripcion (string), $color (string)
--}}
@php
$paletas = [
    'blue'   => ['ring' => 'ring-blue-100',   'bg' => 'bg-blue-50',   'icon' => 'text-blue-500',   'badge' => 'bg-blue-100 text-blue-600'],
    'indigo' => ['ring' => 'ring-indigo-100', 'bg' => 'bg-indigo-50', 'icon' => 'text-indigo-500', 'badge' => 'bg-indigo-100 text-indigo-600'],
    'green'  => ['ring' => 'ring-green-100',  'bg' => 'bg-green-50',  'icon' => 'text-green-500',  'badge' => 'bg-green-100 text-green-600'],
    'yellow' => ['ring' => 'ring-yellow-100', 'bg' => 'bg-yellow-50', 'icon' => 'text-yellow-500', 'badge' => 'bg-yellow-100 text-yellow-600'],
    'purple' => ['ring' => 'ring-purple-100', 'bg' => 'bg-purple-50', 'icon' => 'text-purple-500', 'badge' => 'bg-purple-100 text-purple-600'],
    'orange' => ['ring' => 'ring-orange-100', 'bg' => 'bg-orange-50', 'icon' => 'text-orange-500', 'badge' => 'bg-orange-100 text-orange-600'],
    'amber'  => ['ring' => 'ring-amber-100',  'bg' => 'bg-amber-50',  'icon' => 'text-amber-500',  'badge' => 'bg-amber-100 text-amber-600'],
    'teal'   => ['ring' => 'ring-teal-100',   'bg' => 'bg-teal-50',   'icon' => 'text-teal-500',   'badge' => 'bg-teal-100 text-teal-600'],
    'slate'  => ['ring' => 'ring-slate-100',  'bg' => 'bg-slate-50',  'icon' => 'text-slate-500',  'badge' => 'bg-slate-100 text-slate-600'],
];
$p = $paletas[$color] ?? $paletas['blue'];
@endphp

<div class="min-h-[70vh] flex items-center justify-center">
    <div class="max-w-md w-full text-center">

        {{-- Ícono animado --}}
        <div class="flex justify-center mb-6">
            <div class="relative">
                <div class="w-24 h-24 rounded-3xl {{ $p['bg'] }} {{ $p['ring'] }} ring-8
                            flex items-center justify-center shadow-sm">
                    <svg class="w-12 h-12 {{ $p['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                {{-- Puntito pulsante --}}
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $p['bg'] }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 {{ $p['badge'] }} text-[8px] font-black items-center justify-center">!</span>
                </span>
            </div>
        </div>

        {{-- Badge --}}
        <span class="inline-block text-xs font-bold px-3 py-1 rounded-full {{ $p['badge'] }} mb-4 uppercase tracking-wider">
            Próximamente
        </span>

        {{-- Título --}}
        <h1 class="text-3xl font-black text-primary-dark mb-3">
            Módulo de {{ $modulo }}
        </h1>

        {{-- Descripción --}}
        <p class="text-gray-500 text-sm leading-relaxed mb-8 max-w-xs mx-auto">
            {{ $descripcion }}
            <br><br>
            Estamos trabajando para traerte esta funcionalidad muy pronto.
            Mientras tanto, explora las demás secciones disponibles.
        </p>

        {{-- Barra de progreso decorativa --}}
        <div class="bg-gray-100 rounded-full h-2 mb-2 overflow-hidden mx-auto max-w-xs">
            <div class="h-2 rounded-full bg-gradient-to-r from-[#0BC4D9] to-[#30A9D9] w-1/4
                        animate-[pulse_2s_ease-in-out_infinite]"></div>
        </div>
        <p class="text-xs text-gray-400 mb-8">En desarrollo...</p>

        {{-- Botón volver --}}
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-white
                  bg-gradient-to-r from-[#082B59] to-[#30A9D9]
                  hover:from-[#0BC4D9] hover:to-[#30A9D9]
                  transition-all duration-300 shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al inicio
        </a>

    </div>
</div>
