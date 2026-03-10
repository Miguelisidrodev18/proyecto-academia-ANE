@php
    $estado = $estado ?? 'pendiente';
    $size   = $size ?? 'sm';
    [$clases, $texto] = match($estado) {
        'confirmado' => ['bg-green-100 text-green-700',  'Confirmado'],
        'anulado'    => ['bg-red-100 text-red-700',      'Anulado'],
        default      => ['bg-yellow-100 text-yellow-700','Pendiente'],
    };
@endphp
<span class="inline-flex items-center font-semibold rounded-full
             {{ $size === 'sm' ? 'text-xs px-2.5 py-0.5' : 'text-sm px-3 py-1' }}
             {{ $clases }}">
    {{ $texto }}
</span>
