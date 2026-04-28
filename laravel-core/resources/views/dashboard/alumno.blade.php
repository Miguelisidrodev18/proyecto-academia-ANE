@extends('layouts.dashboard')
@section('title', 'Mi Panel')

@section('content')

{{-- Overlay de racha (fullscreen, solo primera vez del día) --}}
@if($mostrarOverlay && $rachaInfo)
    @include('partials.racha-overlay')
@endif

@php
    $nombre = explode(' ', auth()->user()->name)[0];
    $hora   = now()->hour;
    $saludo = $hora < 12 ? 'Buenos días' : ($hora < 19 ? 'Buenas tardes' : 'Buenas noches');

    // Próxima clase global
    $proximaClaseGlobal = $proximasClases->first();

    // Colores de racha
    if ($rachaInfo) {
        $rr = $rachaInfo['racha_actual'];
        $rEmoji = $rr >= 75 ? '💎' : '🔥';
        if ($rr <= 7)       { $rGrad = 'from-blue-600 to-blue-400';    $rGlow = 'shadow-blue-300/40'; }
        elseif ($rr <= 30)  { $rGrad = 'from-emerald-600 to-emerald-400'; $rGlow = 'shadow-emerald-300/40'; }
        elseif ($rr <= 50)  { $rGrad = 'from-violet-600 to-violet-400'; $rGlow = 'shadow-violet-300/40'; }
        elseif ($rr <= 75)  { $rGrad = 'from-orange-600 to-orange-400'; $rGlow = 'shadow-orange-300/40'; }
        else                { $rGrad = 'from-amber-500 to-yellow-400';  $rGlow = 'shadow-amber-300/40'; }
    }
@endphp

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- HERO                                                        --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="relative rounded-3xl overflow-hidden mb-8 shadow-2xl"
     style="background: linear-gradient(135deg, #082B59 0%, #0f3d7a 40%, #1a5ba0 70%, #30A9D9 100%);">

    {{-- Decoraciones de fondo --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/5"></div>
        <div class="absolute top-8 right-32 w-32 h-32 rounded-full bg-accent/10"></div>
        <div class="absolute -bottom-10 left-1/3 w-48 h-48 rounded-full bg-white/3"></div>
        <div class="absolute bottom-0 right-0 w-80 h-40 opacity-10"
             style="background: radial-gradient(ellipse at right bottom, #0BC4D9 0%, transparent 70%);"></div>
    </div>

    <div class="relative z-10 px-6 py-8 md:px-10 md:py-10 flex flex-col md:flex-row md:items-center gap-6">

        {{-- Texto principal --}}
        <div class="flex-1 min-w-0">
            <p class="text-white/50 text-sm font-medium mb-1">{{ $saludo }},</p>
            <h1 class="text-3xl md:text-4xl font-black text-white leading-tight mb-2">{{ $nombre }}</h1>

            @if($matricula)
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                                 bg-white/15 text-white backdrop-blur-sm border border-white/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Matrícula activa
                    </span>
                    @if($matricula->plan->esVip())
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black
                                     bg-gradient-to-r from-amber-400 to-yellow-300 text-amber-900 shadow-lg">
                            💎 VIP
                        </span>
                    @endif
                    <span class="text-white/50 text-xs">
                        {{ $matricula->plan->tipoIcono() }} {{ $matricula->plan->nombre }}
                        @if(!$matricula->plan->acceso_ilimitado)
                            · {{ $matricula->diasRestantes() }} días restantes
                        @endif
                    </span>
                </div>

                {{-- Próxima clase --}}
                @if($proximaClaseGlobal)
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-4 py-3 max-w-md">
                        <div class="w-10 h-10 rounded-xl bg-accent/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-white/50 text-[10px] font-bold uppercase tracking-wide">Próxima clase</p>
                            <p class="text-white font-bold text-sm truncate">{{ $proximaClaseGlobal->titulo }}</p>
                            <p class="text-white/60 text-xs">{{ $proximaClaseGlobal->fecha->format('d/m/Y') }} · {{ $proximaClaseGlobal->curso->nombre }}</p>
                        </div>
                        @if($proximaClaseGlobal->curso->zoom_link && $matricula->tieneAcceso())
                            <a href="{{ $proximaClaseGlobal->curso->zoom_link }}" target="_blank"
                               class="flex-shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold
                                      bg-accent text-white hover:bg-secondary transition-colors shadow-lg shadow-accent/30">
                                Entrar
                            </a>
                        @endif
                    </div>
                @endif
            @elseif(!$alumno)
                <p class="text-amber-300 text-sm font-medium">Perfil no configurado · Contacta al administrador</p>
            @else
                @php
                    $waRenovar = wa_url(wa_alumno_caducada($nombre));
                @endphp
                <p class="text-white/70 text-sm font-semibold mb-3">¡Tu membresía ha caducado!</p>
                <a href="{{ $waRenovar }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl font-bold text-sm
                          bg-[#25D366] hover:bg-[#1ebe5d] text-white shadow-lg shadow-green-900/40
                          hover:-translate-y-0.5 hover:shadow-xl transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 flex-shrink-0">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
                    </svg>
                    <span>
                        <span class="block text-[10px] font-semibold text-green-100 leading-none mb-0.5">Presiona aquí para renovar</span>
                        <span class="block leading-tight">Escríbenos por WhatsApp</span>
                    </span>
                </a>
            @endif
        </div>

        {{-- Stats compactos --}}
        <div class="flex md:flex-col gap-3 flex-shrink-0">
            {{-- Cursos --}}
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-5 py-4 text-center min-w-[90px]">
                <p class="text-3xl font-black text-white leading-none">{{ $cursos->count() }}</p>
                <p class="text-white/50 text-xs font-medium mt-1">cursos</p>
            </div>
            {{-- Racha --}}
            @if($rachaInfo)
            <div class="bg-gradient-to-br {{ $rGrad }} rounded-2xl px-5 py-4 text-center min-w-[90px] shadow-lg {{ $rGlow }}">
                <p class="text-2xl font-black text-white leading-none">{{ $rEmoji }} {{ $rr }}</p>
                <p class="text-white/70 text-xs font-medium mt-1">{{ $rr === 1 ? 'día' : 'días' }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ANUNCIOS                                                     --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@include('partials.anuncios-banner')

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ALERTA DE VENCIMIENTO                                       --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($matricula && !$matricula->plan->acceso_ilimitado)
@php
    $dias      = $matricula->diasRestantes();
    $saldo     = $matricula->saldoPendiente();
    $esCritico = $dias <= 3;
    $esAviso   = $dias > 3 && $dias <= 7;
@endphp
@if($esCritico || $esAviso)
<div class="mb-6 rounded-2xl overflow-hidden shadow-md
            {{ $esCritico
                ? 'bg-gradient-to-r from-red-600 to-orange-500'
                : 'bg-gradient-to-r from-amber-500 to-yellow-400' }}">
    <div class="px-5 py-4 flex items-start gap-4">

        {{-- Icono --}}
        <div class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center
                    {{ $esCritico ? 'bg-white/20' : 'bg-white/25' }}">
            @if($esCritico)
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            @else
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @endif
        </div>

        {{-- Texto --}}
        <div class="flex-1 min-w-0">
            <p class="text-white font-black text-sm leading-tight">
                @if($esCritico)
                    ⚠️ ¡Tu acceso vence {{ $dias === 0 ? 'hoy' : 'en ' . $dias . ' ' . ($dias === 1 ? 'día' : 'días') }}!
                @else
                    Tu matrícula vence en {{ $dias }} días
                @endif
            </p>
            <p class="text-white/85 text-xs mt-1 leading-snug">
                @if($saldo > 0)
                    Tienes un saldo pendiente de
                    <span class="font-black text-white">S/. {{ number_format($saldo, 2) }}</span>.
                    Si no regularizas tu pago, tu acceso al aula virtual
                    <span class="font-bold {{ $esCritico ? 'underline' : '' }}">será bloqueado automáticamente</span>.
                @else
                    Tu plan <strong class="text-white">{{ $matricula->plan->nombre }}</strong> está próximo a vencer.
                    Renueva a tiempo para no perder el acceso a tus clases y contenido.
                @endif
            </p>
        </div>

        {{-- Badge días --}}
        <div class="flex-shrink-0 text-center bg-white/20 backdrop-blur-sm rounded-xl px-3 py-2 min-w-[52px]">
            <p class="text-white font-black text-lg leading-none">{{ $dias }}</p>
            <p class="text-white/70 text-[10px] font-semibold mt-0.5">{{ $dias === 1 ? 'día' : 'días' }}</p>
        </div>
    </div>

    {{-- Barra de urgencia --}}
    @if($esCritico)
    <div class="h-1 bg-white/20">
        <div class="h-full bg-white/60 animate-pulse" style="width: {{ max(5, ($dias / 3) * 100) }}%"></div>
    </div>
    @endif
</div>
@endif
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- BANNER WHATSAPP: acceso suspendido (cuotas vencidas)        --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($matricula && !$matricula->tieneAcceso())
@php
    $waAcceso = wa_url(wa_alumno_suspendida($nombre));
@endphp
<div class="mb-6 rounded-2xl overflow-hidden shadow-md bg-gradient-to-r from-red-600 to-rose-500">
    <div class="px-5 py-4 flex items-center gap-4">
        <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-white font-black text-sm leading-tight">⚠️ Tu acceso está suspendido</p>
            <p class="text-white/85 text-xs mt-1 leading-snug">
                Tienes pagos pendientes. Contáctanos por WhatsApp para regularizar y recuperar el acceso a tus clases.
            </p>
        </div>
        <a href="{{ $waAcceso }}" target="_blank" rel="noopener"
           class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold
                  bg-[#25D366] hover:bg-[#1ebe5d] text-white shadow-md transition-all duration-200 hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
            </svg>
            Renovar
        </a>
    </div>
</div>
@endif

@if($cursos->isNotEmpty())

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- FILA NETFLIX: MIS CURSOS                                    --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-black text-primary-dark flex items-center gap-2">
            <span class="w-1 h-5 rounded-full bg-accent inline-block"></span>
            Mis Cursos
        </h2>
        <a href="{{ route('alumno.mis-cursos') }}"
           class="text-xs text-accent font-bold hover:text-secondary transition-colors flex items-center gap-1">
            Ver todos
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    {{-- Scroll horizontal estilo Netflix --}}
    <div class="flex gap-4 overflow-x-auto pb-3 -mx-1 px-1 snap-x snap-mandatory scrollbar-hide"
         style="scrollbar-width: none; -ms-overflow-style: none;">
        @foreach($cursos as $curso)
        @php
            $proximaClaseCurso = $curso->clases->first();
            $gradients = [
                'pollito'    => ['from-blue-700 to-blue-500',    'bg-blue-600/20'],
                'intermedio' => ['from-[#082B59] to-[#30A9D9]', 'bg-accent/20'],
                'ambos'      => ['from-violet-700 to-violet-500','bg-violet-600/20'],
            ];
            [$grad, $iconBg] = $gradients[$curso->nivel] ?? $gradients['intermedio'];
        @endphp
        <div class="group flex-shrink-0 w-56 snap-start">
            <a href="{{ route('alumno.mis-cursos') }}"
               class="block relative rounded-2xl overflow-hidden shadow-md
                      hover:shadow-xl hover:-translate-y-1.5 hover:scale-[1.02]
                      transition-all duration-300 cursor-pointer">

                {{-- Imagen / placeholder con gradiente --}}
                <div class="h-36 relative overflow-hidden
                            bg-gradient-to-br {{ $grad }}">
                    @if($curso->imagen_url)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($curso->imagen_url) }}" alt="{{ $curso->nombre }}"
                             class="absolute inset-0 w-full h-full object-cover opacity-60
                                    group-hover:opacity-80 group-hover:scale-110 transition-all duration-500">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center opacity-20">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Overlay hover --}}
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300
                                    bg-white/20 backdrop-blur-sm rounded-full p-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Badge nivel --}}
                    <div class="absolute top-2 left-2">
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wide
                                     bg-black/30 backdrop-blur-sm text-white/90">
                            {{ $curso->nivelLabel() }}
                        </span>
                    </div>

                    {{-- Indicador próxima clase --}}
                    @if($proximaClaseCurso)
                    <div class="absolute bottom-2 right-2">
                        <span class="flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold
                                     bg-emerald-500/80 backdrop-blur-sm text-white">
                            <span class="w-1 h-1 rounded-full bg-white animate-pulse"></span>
                            Próxima clase
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="bg-white px-4 py-3">
                    <h3 class="font-black text-sm text-gray-800 leading-tight truncate">{{ $curso->nombre }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $curso->tipoLabel() }}
                        @if($curso->grado) · {{ $curso->gradoLabel() }}@endif
                    </p>
                    @if($proximaClaseCurso)
                        <p class="text-[10px] text-accent font-semibold mt-1.5 truncate">
                            📅 {{ $proximaClaseCurso->fecha->format('d/m H:i') }} — {{ Str::limit($proximaClaseCurso->titulo, 22) }}
                        </p>
                    @endif
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- PRÓXIMAS CLASES                                             --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($proximasClases->isNotEmpty())
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-black text-primary-dark flex items-center gap-2">
            <span class="w-1 h-5 rounded-full bg-emerald-500 inline-block"></span>
            Próximas Clases
        </h2>
    </div>

    <div class="flex gap-4 overflow-x-auto pb-3 -mx-1 px-1 snap-x snap-mandatory"
         style="scrollbar-width: none; -ms-overflow-style: none;">
        @foreach($proximasClases as $clase)
        @php
            $diasParaClase = now()->startOfDay()->diffInDays($clase->fecha->startOfDay(), false);
            $estaHoy = $diasParaClase === 0;
            $esManana = $diasParaClase === 1;
        @endphp
        <div class="flex-shrink-0 w-64 snap-start">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                        hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 h-full flex flex-col">

                {{-- Cabecera con countdown --}}
                <div class="{{ $estaHoy ? 'bg-gradient-to-r from-emerald-600 to-emerald-400' : ($esManana ? 'bg-gradient-to-r from-accent to-secondary' : 'bg-gradient-to-r from-primary-dark to-primary-light') }}
                            px-4 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-white/60 text-[10px] font-bold uppercase tracking-wide">
                            {{ $estaHoy ? 'HOY' : ($esManana ? 'MAÑANA' : 'EN ' . $diasParaClase . ' DÍAS') }}
                        </p>
                        <p class="text-white font-black text-sm">{{ $clase->fecha->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($estaHoy)
                        <span class="flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-white/20 text-white">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            Hoy
                        </span>
                    @endif
                </div>

                <div class="px-4 py-3 flex-1 flex flex-col justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide mb-0.5">{{ $clase->curso->nombre }}</p>
                        <h3 class="font-black text-sm text-gray-800 leading-tight">{{ $clase->titulo }}</h3>
                        @if($clase->descripcion)
                            <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $clase->descripcion }}</p>
                        @endif
                    </div>
                    @if($clase->curso->zoom_link && $matricula->tieneAcceso())
                        <a href="{{ $clase->curso->zoom_link }}" target="_blank"
                           class="mt-3 w-full flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold text-white
                                  {{ $estaHoy ? 'bg-gradient-to-r from-emerald-600 to-emerald-400 shadow-lg shadow-emerald-300/40' : 'bg-gradient-to-r from-primary-dark to-primary-light' }}
                                  hover:opacity-90 transition-opacity">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Unirse por Zoom
                        </a>
                    @else
                        <div class="mt-3 w-full flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-medium
                                    text-gray-400 bg-gray-50 border border-gray-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Link próximamente
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- BLOQUE DE PROGRESO / RACHA                                  --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($rachaInfo)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-2">

    {{-- Racha visual --}}
    <div class="bg-gradient-to-br {{ $rGrad }} rounded-2xl p-5 shadow-lg {{ $rGlow }} relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-white/10"></div>
        <div class="text-3xl mb-2">{{ $rEmoji }}</div>
        <p class="text-white/60 text-xs font-semibold uppercase tracking-wide">Racha de acceso</p>
        <p class="text-4xl font-black text-white mt-1">{{ $rr }}</p>
        <p class="text-white/60 text-xs mt-1">{{ $rr === 1 ? 'día consecutivo' : 'días consecutivos' }}</p>
    </div>

    {{-- Racha anterior --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-3">
            <span class="text-xl">🏆</span>
        </div>
        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide">Racha anterior</p>
        <p class="text-3xl font-black text-gray-800 mt-1">{{ $rachaInfo['racha_anterior'] }}</p>
        <p class="text-gray-400 text-xs mt-1">{{ $rachaInfo['racha_anterior'] === 1 ? 'día' : 'días' }} previos</p>
    </div>

    {{-- Estado del día --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm sm:col-span-2 lg:col-span-1">
        <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide">Acceso de hoy</p>
        @if($rachaInfo['mismo_dia'])
            <p class="text-3xl font-black text-emerald-600 mt-1">✓</p>
            <p class="text-gray-400 text-xs mt-1">Ya registrado hoy</p>
        @elseif($rachaInfo['si_subio_racha'])
            <p class="text-3xl font-black text-accent mt-1">+1</p>
            <p class="text-gray-400 text-xs mt-1">¡Racha extendida!</p>
        @else
            <p class="text-3xl font-black text-amber-500 mt-1">↺</p>
            <p class="text-gray-400 text-xs mt-1">Racha reiniciada</p>
        @endif
    </div>
</div>
@endif

@else
{{-- Sin cursos --}}
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm py-20 text-center">
    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-5 shadow-inner">
        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
        </svg>
    </div>
    <p class="text-gray-600 font-bold text-base">
        @if(!$alumno)
            Perfil de alumno no configurado
        @elseif(!$matricula)
            Sin matrícula activa
        @else
            Contenido en preparación
        @endif
    </p>
    <p class="text-gray-400 text-sm mt-2 max-w-xs mx-auto">
        @if(!$alumno)
            Contacta al administrador para completar tu registro.
        @elseif(!$matricula)
            Comunícate con la academia para matricularte en un plan.
        @else
            Los cursos de tu plan estarán disponibles muy pronto.
        @endif
    </p>
</div>
@endif

@endsection
