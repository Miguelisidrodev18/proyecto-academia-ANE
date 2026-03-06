@props([
    'plan'     => 'Premium',
    'price'    => '250',
    'period'   => '3 meses',
    'badge'    => null,
    'featured' => false,
    'features' => [],
])

<div @class([
    'relative flex flex-col rounded-2xl shadow-xl overflow-hidden transition-transform duration-300 hover:-translate-y-1',
    'bg-primary-dark text-white ring-4 ring-accent'  => $featured,
    'bg-white text-gray-800'                          => !$featured,
])>

    <!-- Badge -->
    @if($badge)
        <div class="absolute top-4 right-4">
            <span @class([
                'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide',
                'bg-accent text-white'       => $featured,
                'bg-primary-dark text-white' => !$featured,
            ])>
                {{ $badge }}
            </span>
        </div>
    @endif

    <!-- Cabecera -->
    <div @class([
        'px-8 pt-8 pb-6',
        'bg-white/10' => $featured,
        'bg-brand-bg' => !$featured,
    ])>
        <h3 @class([
            'text-2xl font-extrabold mb-1',
            'text-accent'        => $featured,
            'text-primary-dark'  => !$featured,
        ])>
            {{ $plan }}
        </h3>
        <div class="flex items-end gap-1 mt-3">
            <span class="text-4xl font-black">S/ {{ $price }}</span>
            <span @class([
                'text-sm mb-1',
                'text-white/60'   => $featured,
                'text-gray-500'   => !$featured,
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
                        'text-accent'       => $featured,
                        'text-primary-dark' => !$featured,
                    ]) fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    <span @class(['text-white/80' => $featured])>{{ $feature }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- CTA -->
    <div class="px-8 pb-8">
        <a href="{{ route('register') }}"
           @class([
               'block w-full text-center py-3 rounded-xl font-bold text-sm transition-all duration-200 shadow-md',
               'bg-accent text-white hover:bg-secondary'                                => $featured,
               'bg-primary-dark text-white hover:bg-accent'                              => !$featured,
           ])>
            Empezar ahora
        </a>
    </div>
</div>
