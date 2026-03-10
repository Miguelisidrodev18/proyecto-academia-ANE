@php
    $estado = $estado ?? 'pendiente';
    $config = match($estado) {
        'activa'    => ['bg-green-100 text-green-700 ring-1 ring-green-200',  'Activa'],
        'vencida'   => ['bg-yellow-100 text-yellow-700 ring-1 ring-yellow-200','Vencida'],
        'suspendida'=> ['bg-red-100 text-red-700 ring-1 ring-red-200',        'Suspendida'],
        default     => ['bg-gray-100 text-gray-600 ring-1 ring-gray-200',     'Pendiente'],
    };
@endphp
<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $config[0] }}">
    <span class="w-1.5 h-1.5 rounded-full
        {{ $estado === 'activa' ? 'bg-green-500' : ($estado === 'vencida' ? 'bg-yellow-500' : ($estado === 'suspendida' ? 'bg-red-500' : 'bg-gray-400')) }}">
    </span>
    {{ $config[1] }}
</span>
