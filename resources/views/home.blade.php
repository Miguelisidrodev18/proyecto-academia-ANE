<x-layouts.landing>

    {{-- ============================================================
         HERO SECTION
    ============================================================ --}}
    <section class="relative bg-primary-dark overflow-hidden">
        <!-- Fondo decorativo con gradiente -->
        <div class="absolute inset-0 opacity-20"
             style="background: radial-gradient(ellipse at 70% 50%, #0BC4D9 0%, transparent 60%),
                               radial-gradient(ellipse at 10% 80%, #30A9D9 0%, transparent 50%);">
        </div>

        <!-- Círculos decorativos -->
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-accent/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-primary-light/10 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-36 text-center">
            <!-- Badge -->
            <span class="inline-block px-4 py-1.5 rounded-full bg-accent/20 text-accent text-xs font-semibold
                         uppercase tracking-widest mb-6 border border-accent/30">
                Plataforma educativa #1 en el Perú
            </span>

            <!-- Título -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight max-w-4xl mx-auto">
                Aprende sin
                <span class="text-accent"> límites</span>,
                crece sin
                <span class="text-primary-light"> fronteras</span>
            </h1>

            <!-- Subtítulo -->
            <p class="mt-6 text-lg sm:text-xl text-white/70 max-w-2xl mx-auto leading-relaxed">
                Accede a cursos de calidad, aprende a tu ritmo y transforma tu carrera
                con la guía de expertos certificados.
            </p>

            <!-- Botones CTA -->
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#planes"
                   class="px-8 py-4 rounded-xl bg-accent text-white font-bold text-base
                          hover:bg-secondary transition-all duration-200 shadow-lg shadow-accent/30
                          hover:shadow-secondary/40 hover:-translate-y-0.5">
                    Ver planes
                </a>
                <a href="{{ route('register') }}"
                   class="px-8 py-4 rounded-xl border-2 border-white/30 text-white font-bold text-base
                          hover:border-accent hover:text-accent transition-all duration-200">
                    Crear cuenta gratis
                </a>
            </div>

            <!-- Stats -->
            <div class="mt-16 grid grid-cols-3 gap-6 max-w-lg mx-auto">
                <div class="text-center">
                    <div class="text-3xl font-black text-accent">500+</div>
                    <div class="text-white/60 text-xs mt-1">Cursos</div>
                </div>
                <div class="text-center border-x border-white/10">
                    <div class="text-3xl font-black text-accent">10K+</div>
                    <div class="text-white/60 text-xs mt-1">Estudiantes</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black text-accent">98%</div>
                    <div class="text-white/60 text-xs mt-1">Satisfacción</div>
                </div>
            </div>
        </div>

        <!-- Onda separadora -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 60L1440 60L1440 20C1440 20 1080 60 720 40C360 20 0 60 0 60Z"
                      fill="#F2F2F2"/>
            </svg>
        </div>
    </section>

    {{-- ============================================================
         PLANES
    ============================================================ --}}
    <section id="planes" class="bg-brand-bg py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Encabezado de sección -->
            <div class="text-center mb-14">
                <span class="inline-block px-4 py-1.5 rounded-full bg-accent/10 text-accent
                             text-xs font-semibold uppercase tracking-widest mb-3 border border-accent/20">
                    Precios
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-primary-dark">
                    Elige el plan perfecto para ti
                </h2>
                <p class="mt-3 text-gray-500 max-w-xl mx-auto">
                    Sin sorpresas ni costos ocultos. Paga una vez y accede a todo el contenido.
                </p>
            </div>

            <!-- Tarjetas de precios -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">

                <x-price-card
                    plan="Premium"
                    price="250"
                    period="3 meses"
                    badge="Popular"
                    :features="[
                        'Acceso a +200 cursos',
                        'Certificados descargables',
                        'Soporte por correo',
                        'Actualizaciones incluidas',
                        'Comunidad de estudiantes',
                    ]"
                />

                <x-price-card
                    plan="VIP"
                    price="500"
                    period="Ilimitado"
                    badge="Mejor valor"
                    :featured="true"
                    :features="[
                        'Todo lo del plan Premium',
                        'Acceso ilimitado de por vida',
                        'Soporte prioritario 24/7',
                        'Mentoría personalizada',
                        'Acceso anticipado a cursos',
                    ]"
                />

            </div>
        </div>
    </section>

    {{-- ============================================================
         POR QUÉ ELEGIRNOS
    ============================================================ --}}
    <section class="bg-white py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Encabezado de sección -->
            <div class="text-center mb-14">
                <span class="inline-block px-4 py-1.5 rounded-full bg-primary-dark/10 text-primary-dark
                             text-xs font-semibold uppercase tracking-widest mb-3 border border-primary-dark/10">
                    Beneficios
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-primary-dark">
                    ¿Por qué elegirnos?
                </h2>
                <p class="mt-3 text-gray-500 max-w-xl mx-auto">
                    Más que una plataforma, somos tu compañero de aprendizaje.
                </p>
            </div>

            <!-- Cards de beneficios -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">

                <!-- Beneficio 1 -->
                <div class="flex flex-col items-center text-center p-8 rounded-2xl bg-brand-bg
                            hover:shadow-lg transition-shadow duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-accent/15 flex items-center justify-center mb-5
                                group-hover:bg-accent/25 transition-colors duration-300">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary-dark mb-2">Contenido de calidad</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Cursos diseñados por expertos con años de experiencia en la industria,
                        actualizados constantemente con las últimas tendencias.
                    </p>
                </div>

                <!-- Beneficio 2 -->
                <div class="flex flex-col items-center text-center p-8 rounded-2xl bg-brand-bg
                            hover:shadow-lg transition-shadow duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-primary-light/15 flex items-center justify-center mb-5
                                group-hover:bg-primary-light/25 transition-colors duration-300">
                        <svg class="w-8 h-8 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary-dark mb-2">Aprende a tu ritmo</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Sin horarios fijos. Accede desde cualquier dispositivo cuando quieras,
                        y avanza según tu disponibilidad y preferencias.
                    </p>
                </div>

                <!-- Beneficio 3 -->
                <div class="flex flex-col items-center text-center p-8 rounded-2xl bg-brand-bg
                            hover:shadow-lg transition-shadow duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-secondary/15 flex items-center justify-center mb-5
                                group-hover:bg-secondary/25 transition-colors duration-300">
                        <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary-dark mb-2">Certificados válidos</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Obtén certificados reconocidos al completar cada curso y
                        destaca tu perfil profesional ante empleadores y clientes.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================================
         LLAMADA A LA ACCIÓN FINAL
    ============================================================ --}}
    <section class="bg-primary-dark py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">
                ¿Listo para empezar?
            </h2>
            <p class="text-white/60 text-lg mb-8">
                Únete a miles de estudiantes que ya están transformando su carrera.
            </p>
            <a href="{{ route('register') }}"
               class="inline-block px-10 py-4 rounded-xl bg-accent text-white font-bold text-base
                      hover:bg-secondary transition-all duration-200 shadow-lg shadow-accent/30">
                Registrarse gratis
            </a>
        </div>
    </section>

</x-layouts.landing>
