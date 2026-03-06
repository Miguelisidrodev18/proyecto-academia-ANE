<footer class="bg-primary-dark text-white" id="contacto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            <!-- Columna Logo -->
            <div class="flex flex-col gap-4">
                <img src="{{ asset('images/logo-academia.png') }}"
                     alt="Academia Nueva Era"
                     class="h-12 w-auto object-contain self-start">
                <p class="text-white/70 text-sm leading-relaxed">
                    Impulsamos tu aprendizaje con tecnología y metodologías innovadoras.
                    Únete a nuestra comunidad y transforma tu futuro.
                </p>
            </div>

            <!-- Columna Links -->
            <div>
                <h3 class="text-accent font-semibold text-sm uppercase tracking-widest mb-4">
                    Navegación
                </h3>
                <ul class="flex flex-col gap-2">
                    <li>
                        <a href="{{ route('home') }}"
                           class="text-white/70 hover:text-accent transition-colors duration-200 text-sm">
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="#planes"
                           class="text-white/70 hover:text-accent transition-colors duration-200 text-sm">
                            Planes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}"
                           class="text-white/70 hover:text-accent transition-colors duration-200 text-sm">
                            Ingresar
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}"
                           class="text-white/70 hover:text-accent transition-colors duration-200 text-sm">
                            Registrarse
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Columna Contacto -->
            <div>
                <h3 class="text-accent font-semibold text-sm uppercase tracking-widest mb-4">
                    Contacto
                </h3>
                <ul class="flex flex-col gap-3 text-sm text-white/70">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        contacto@academianueva-era.com
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        +51 999 888 777
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Lima, Perú
                    </li>
                </ul>
            </div>
        </div>

        <!-- Divisor y copyright -->
        <div class="border-t border-white/10 mt-10 pt-6 text-center">
            <p class="text-white/50 text-sm">
                &copy; {{ date('Y') }} Academia Nueva Era. Todos los derechos reservados.
            </p>
        </div>
    </div>
</footer>
