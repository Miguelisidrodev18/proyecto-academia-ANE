{{--
    Stepper del flujo rápido: Alumno → Matrícula → Pago
    Variables:
        $flowStep       int  (1, 2 o 3)  — paso activo
        $flowAlumno     Alumno|null      — alumno ya creado
        $flowMatricula  Matricula|null   — matrícula ya creada
--}}
@php
    $flowAlumno    = $flowAlumno    ?? null;
    $flowMatricula = $flowMatricula ?? null;

    $steps = [
        1 => [
            'label'    => 'Alumno',
            'sublabel' => $flowAlumno ? $flowAlumno->nombreCompleto() : 'Datos personales',
            'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
        ],
        2 => [
            'label'    => 'Matrícula',
            'sublabel' => $flowMatricula ? $flowMatricula->plan->nombre : 'Plan y fechas',
            'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
        ],
        3 => [
            'label'    => 'Pago',
            'sublabel' => 'Método y monto',
            'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        ],
    ];
@endphp

<div class="mb-6 no-print">
    {{-- Banner de flujo --}}
    <div class="flex items-center gap-2 mb-4 px-1">
        <div class="w-5 h-5 rounded-full bg-accent flex items-center justify-center flex-shrink-0">
            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <p class="text-xs font-bold text-accent uppercase tracking-wider">Registro rápido</p>
        <div class="flex-1 h-px bg-accent/20"></div>
    </div>

    {{-- Steps --}}
    <div class="relative flex items-start gap-0">
        @foreach($steps as $num => $step)
        @php
            $done   = $num < $flowStep || $flowStep > count($steps);
            $active = $num === $flowStep;
            $future = $num > $flowStep && $flowStep <= count($steps);
        @endphp

        {{-- Step --}}
        <div class="flex-1 flex flex-col items-center relative">

            {{-- Línea conectora izquierda --}}
            @if($num > 1)
            <div class="absolute top-4 right-1/2 left-0 h-0.5 -translate-y-1/2
                        {{ $done || $active ? 'bg-gradient-to-r from-emerald-400 to-accent' : 'bg-gray-200' }}"></div>
            @endif
            {{-- Línea conectora derecha --}}
            @if($num < count($steps))
            <div class="absolute top-4 left-1/2 right-0 h-0.5 -translate-y-1/2
                        {{ $done ? 'bg-gradient-to-r from-accent to-emerald-400' : 'bg-gray-200' }}"></div>
            @endif

            {{-- Círculo --}}
            <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300
                        {{ $done   ? 'bg-emerald-500 shadow-md shadow-emerald-200' : '' }}
                        {{ $active ? 'bg-accent shadow-md shadow-accent/40 ring-4 ring-accent/20' : '' }}
                        {{ $future ? 'bg-gray-100 border-2 border-gray-200' : '' }}">
                @if($done)
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                @elseif($active)
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $step['icon'] !!}
                    </svg>
                @else
                    <span class="text-xs font-black text-gray-400">{{ $num }}</span>
                @endif
            </div>

            {{-- Label --}}
            <div class="mt-2 text-center px-1">
                <p class="text-xs font-bold leading-tight
                           {{ $done   ? 'text-emerald-600' : '' }}
                           {{ $active ? 'text-accent' : '' }}
                           {{ $future ? 'text-gray-400' : '' }}">
                    {{ $step['label'] }}
                </p>
                <p class="text-[10px] leading-tight mt-0.5 max-w-[80px] truncate
                           {{ $done   ? 'text-emerald-500' : '' }}
                           {{ $active ? 'text-accent/70' : '' }}
                           {{ $future ? 'text-gray-300' : '' }}">
                    {{ $step['sublabel'] }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
