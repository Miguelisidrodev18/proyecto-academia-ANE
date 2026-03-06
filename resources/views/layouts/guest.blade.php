<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Academia Nueva Era') }} - @yield('title', 'Inicio')</title>

        <!-- Fonts - Inter (más moderno que Figtree) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Animaciones personalizadas */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            @keyframes pulse-glow {
                0%, 100% { opacity: 0.3; transform: scale(1); }
                50% { opacity: 0.5; transform: scale(1.05); }
            }
            @keyframes slideInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-50px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(50px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            .animate-pulse-glow {
                animation: pulse-glow 4s ease-in-out infinite;
            }
            .animate-slide-left {
                animation: slideInLeft 0.8s ease-out forwards;
            }
            .animate-slide-right {
                animation: slideInRight 0.8s ease-out forwards;
            }
            .animate-fade-up {
                animation: fadeInUp 0.6s ease-out forwards;
                opacity: 0;
            }
            .delay-1 { animation-delay: 0.1s; }
            .delay-2 { animation-delay: 0.2s; }
            .delay-3 { animation-delay: 0.3s; }
            .delay-4 { animation-delay: 0.4s; }
        </style>
    </head>
    <body class="font-['Inter'] antialiased">
        <div class="min-h-screen flex bg-gradient-to-br from-[#F2F2F2] to-white">

            <!-- Panel izquierdo decorativo (solo en desktop) -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#082B59] to-[#0A3A7A] flex-col items-center justify-center py-10 px-12 relative overflow-hidden">
                <!-- Elementos decorativos animados -->
                <div class="absolute top-20 left-20 w-72 h-72 rounded-full bg-[#0BC4D9]/20 blur-3xl animate-pulse-glow"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 rounded-full bg-[#30A9D9]/10 blur-3xl animate-float"></div>
                <div class="absolute top-40 right-40 w-48 h-48 rounded-full bg-white/5 blur-2xl"></div>

                <!-- Patrón de fondo con puntos -->
                <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>

                <div class="relative text-center animate-slide-left w-full max-w-xs mx-auto">
                    <a href="{{ route('home') }}" class="inline-block transform hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('images/logo-academia.png') }}"
                             alt="Academia Nueva Era"
                             class="h-20 w-auto mx-auto mb-8 object-contain drop-shadow-lg">
                    </a>

                    <h2 class="text-4xl font-black text-white leading-tight mb-4">
                        Tu ingreso a la<br>
                        <span class="text-[#0BC4D9] relative inline-block">
                            universidad soñada
                            <span class="absolute -bottom-2 left-0 w-full h-1 bg-[#0BC4D9]/30 rounded-full"></span>
                        </span><br>
                        <span class="text-white/90">empieza aquí</span>
                    </h2>

                    <p class="mt-6 text-white/60 text-sm leading-relaxed max-w-xs mx-auto">
                        Academia pre-universitaria especializada en exámenes de admisión
                        para las mejores universidades del Perú.
                    </p>

                    <!-- Universidades destino -->
                    <div class="mt-6 flex flex-wrap justify-center gap-2 max-w-xs mx-auto">
                        @foreach(['UNMSM','PUCP','UNI','UPCH','UNFV','UNALM'] as $uni)
                            <span class="px-2.5 py-1 rounded-lg bg-white/10 text-white/70 text-xs font-semibold
                                         border border-white/10 hover:bg-[#0BC4D9]/20 hover:text-white
                                         transition-all duration-200 cursor-default">
                                {{ $uni }}
                            </span>
                        @endforeach
                    </div>

                    <!-- Estadísticas rápidas -->
                    <div class="mt-8 grid grid-cols-3 gap-4 max-w-xs mx-auto">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">3,500+</div>
                            <div class="text-xs text-white/40">Ingresantes</div>
                        </div>
                        <div class="text-center border-x border-white/10">
                            <div class="text-2xl font-bold text-white">15+</div>
                            <div class="text-xs text-white/40">Universidades</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">98%</div>
                            <div class="text-xs text-white/40">Aprobación</div>
                        </div>
                    </div>

                    <ul class="mt-8 flex flex-col gap-4 text-left max-w-xs mx-auto">
                        @foreach([
                            ['Simulacros basados en exámenes reales', '📝'],
                            ['Docentes expertos en admisión universitaria', '👨‍🏫'],
                            ['Seguimiento personalizado hasta tu ingreso', '🎯']
                        ] as $index => $item)
                            <li class="flex items-center gap-3 text-white/70 text-sm group hover:text-white transition-colors duration-300 animate-fade-up delay-{{ $index + 1 }}">
                                <span class="text-xl group-hover:scale-110 transition-transform duration-300">{{ $item[1] }}</span>
                                <span class="flex-1">{{ $item[0] }}</span>
                                <svg class="w-4 h-4 text-[#0BC4D9] opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Testimonio -->
                    <div class="mt-8 bg-white/5 backdrop-blur-lg rounded-xl p-4 border border-white/10 animate-fade-up delay-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#0BC4D9]/20 flex items-center justify-center text-[#0BC4D9] font-bold text-sm flex-shrink-0">MG</div>
                            <div class="flex-1">
                                <p class="text-white/80 text-xs italic leading-relaxed">"Ingresé a la UNMSM en mi primer intento. Los simulacros de la academia son idénticos al examen real."</p>
                                <p class="text-white/40 text-[10px] mt-1.5">— María García · UNMSM Medicina Humana</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel derecho con el formulario -->
            <div class="w-full lg:w-1/2 flex flex-col items-center justify-center px-6 py-12 animate-slide-right">
                <!-- Logo en móvil -->
                <a href="{{ route('home') }}" class="lg:hidden mb-8 transform hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('images/logo-academia.png') }}"
                         alt="Academia Nueva Era"
                         class="h-12 w-auto object-contain">
                </a>

                <!-- Tarjeta blanca con el formulario -->
                <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl px-8 py-10 relative overflow-hidden">
                    <!-- Decoración superior -->
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#0BC4D9] via-[#30A9D9] to-[#082B59]"></div>

                    <!-- Logo pequeño dentro de la tarjeta (solo desktop) -->
                    <div class="hidden lg:flex justify-center mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#0BC4D9] to-[#30A9D9] flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>

                    {{ $slot }}

                    <!-- Separador decorativo -->
                    <div class="relative mt-8">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-4 bg-white text-gray-400">Plataforma segura</span>
                        </div>
                    </div>

                    <!-- Iconos de seguridad -->
                    <div class="flex justify-center gap-4 mt-4">
                        <svg class="w-5 h-5 text-gray-300 hover:text-[#0BC4D9] transition-colors duration-300 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <svg class="w-5 h-5 text-gray-300 hover:text-[#30A9D9] transition-colors duration-300 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <svg class="w-5 h-5 text-gray-300 hover:text-[#082B59] transition-colors duration-300 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>

                <!-- Footer simple para móvil -->
                <p class="lg:hidden text-center text-xs text-gray-400 mt-8">
                    &copy; {{ date('Y') }} Academia Nueva Era. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </body>
</html>