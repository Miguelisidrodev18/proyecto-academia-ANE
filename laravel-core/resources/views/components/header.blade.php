<header class="bg-primary-dark shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0">
                <img src="{{ asset('images/logo-academia.png') }}"
                     alt="Academia Nueva Era"
                     class="h-10 lg:h-12 w-auto object-contain">
                <div class="leading-tight">
                    <p class="text-white font-bold text-sm leading-none">Academia</p>
                    <p class="text-accent font-black text-sm leading-none">Nueva Era Estudiantil</p>
                </div>
            </a>

            <!-- Menú desktop -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}"
                   class="text-white font-medium hover:text-accent transition-colors duration-200">
                    Inicio
                </a>
                <a href="#planes"
                   class="text-white font-medium hover:text-accent transition-colors duration-200">
                    Planes
                </a>
                <a href="#contacto"
                   class="text-white font-medium hover:text-accent transition-colors duration-200">
                    Contacto
                </a>
            </nav>

            <!-- Botones desktop -->
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="px-5 py-2 rounded-lg border-2 border-accent text-accent font-semibold
                          hover:bg-accent hover:text-white transition-all duration-200 text-sm">
                    Ingresar
                </a>
                <a href="#contacto"
                   class="px-5 py-2 rounded-lg bg-accent text-white font-semibold
                          hover:bg-secondary transition-all duration-200 text-sm shadow-md">
                    Registrarse
                </a>
            </div>

            <!-- Botón hamburguesa móvil -->
            <button id="menu-toggle"
                    class="md:hidden text-white focus:outline-none"
                    aria-label="Abrir menú">
                <svg id="icon-open" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="icon-close" class="w-7 h-7 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Menú móvil -->
    <div id="mobile-menu" class="hidden md:hidden bg-primary-dark border-t border-white/10 px-4 pb-4">
        <nav class="flex flex-col gap-3 pt-3">
            <a href="{{ route('home') }}"
               class="text-white font-medium hover:text-accent transition-colors duration-200 py-1">
                Inicio
            </a>
            <a href="#planes"
               class="text-white font-medium hover:text-accent transition-colors duration-200 py-1">
                Planes
            </a>
            <a href="#contacto"
               class="text-white font-medium hover:text-accent transition-colors duration-200 py-1">
                Contacto
            </a>
        </nav>
        <div class="flex flex-col gap-3 mt-4">
            <a href="{{ route('login') }}"
               class="w-full text-center px-5 py-2 rounded-lg border-2 border-accent text-accent font-semibold
                      hover:bg-accent hover:text-white transition-all duration-200 text-sm">
                Ingresar
            </a>
            <a href="#contacto"
               class="w-full text-center px-5 py-2 rounded-lg bg-accent text-white font-semibold
                      hover:bg-secondary transition-all duration-200 text-sm shadow-md">
                Registrarse
            </a>
        </div>
    </div>
</header>

<script>
    const toggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('icon-open');
    const iconClose = document.getElementById('icon-close');

    toggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
    });
</script>
