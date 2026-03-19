@props([
    'plan'     => 'Premium',
    'price'    => '250',
    'period'   => '3 meses',
    'badge'    => null,
    'featured' => false,
    'vip'      => false,
    'features' => [],
])

<div @class([
    'relative flex flex-col rounded-2xl shadow-xl overflow-hidden transition-transform duration-300 hover:-translate-y-1',
    'ring-4 ring-amber-400 shadow-amber-200'  => $vip,
    'bg-primary-dark text-white ring-4 ring-accent' => $featured && !$vip,
    'bg-white text-gray-800'                  => !$featured && !$vip,
])
     style="{{ $vip ? 'background: linear-gradient(135deg, #1a0a00 0%, #3d1f00 40%, #6b3500 100%);' : '' }}">

    {{-- VIP glow overlay --}}
    @if($vip)
        <div class="absolute inset-0 pointer-events-none"
             style="background: radial-gradient(ellipse at 80% 0%, rgba(251,191,36,0.15) 0%, transparent 60%),
                                radial-gradient(ellipse at 10% 100%, rgba(217,119,6,0.1) 0%, transparent 50%);"></div>
    @endif

    <!-- Badge -->
    @if($badge || $vip)
        <div class="absolute top-4 right-4 flex items-center gap-1.5">
            @if($vip)
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wide
                             bg-gradient-to-r from-amber-400 to-yellow-300 text-amber-900 shadow-lg shadow-amber-400/40">
                    💎 VIP
                </span>
            @elseif($badge)
                <span @class([
                    'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide',
                    'bg-accent text-white'       => $featured,
                    'bg-primary-dark text-white' => !$featured,
                ])>
                    {{ $badge }}
                </span>
            @endif
        </div>
    @endif

    <!-- Cabecera -->
    <div @class([
        'px-8 pt-8 pb-6',
        'bg-white/5'  => $vip,
        'bg-white/10' => $featured && !$vip,
        'bg-brand-bg' => !$featured && !$vip,
    ])>
        <h3 @class([
            'text-2xl font-extrabold mb-1',
            'text-amber-300'     => $vip,
            'text-accent'        => $featured && !$vip,
            'text-primary-dark'  => !$featured && !$vip,
        ])>
            {{ $plan }}
        </h3>
        <div class="flex items-end gap-1 mt-3">
            <span @class([
                'text-4xl font-black',
                'text-white' => $vip || $featured,
            ])>S/ {{ $price }}</span>
            <span @class([
                'text-sm mb-1',
                'text-amber-300/70' => $vip,
                'text-white/60'     => $featured && !$vip,
                'text-gray-500'     => !$featured && !$vip,
            ])>/ {{ $period }}</span>
        </div>
    </div>

    <!-- Features -->
    <div class="px-8 py-6 flex-1">
        <ul class="flex flex-col gap-3">
            @foreach($features as $feature)
                <li class="flex items-center gap-3 text-sm">
                    <svg @class([
                        'w-5 h-5 flex-shrink-0',
                        'text-amber-400'    => $vip,
                        'text-accent'       => $featured && !$vip,
                        'text-primary-dark' => !$featured && !$vip,
                    ]) fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    <span @class([
                        'text-amber-100/90' => $vip,
                        'text-white/80'     => $featured && !$vip,
                    ])>{{ $feature }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- CTA -->
    <div class="px-8 pb-8">
        <a href="{{ route('register') }}"
           @class([
               'block w-full text-center py-3 rounded-xl font-bold text-sm transition-all duration-200 shadow-md',
               'bg-gradient-to-r from-amber-400 to-yellow-300 text-amber-900 hover:from-amber-300 hover:to-yellow-200 shadow-amber-400/40' => $vip,
               'bg-accent text-white hover:bg-secondary'         => $featured && !$vip,
               'bg-primary-dark text-white hover:bg-accent'      => !$featured && !$vip,
           ])>
            @if($vip) 💎 @endif Empezar ahora
        </a>
    </div>
</div>
