<x-layouts.landing>

    {{-- ============================================================
         HERO SECTION
    ============================================================ --}}
    <section class="relative bg-primary-dark overflow-hidden">
        <!-- Gradiente de fondo -->
        <div class="absolute inset-0 opacity-25"
             style="background: radial-gradient(ellipse at 75% 40%, #0BC4D9 0%, transparent 55%),
                               radial-gradient(ellipse at 5% 90%, #30A9D9 0%, transparent 50%);">
        </div>
        <!-- Patrón de puntos -->
        <div class="absolute inset-0 opacity-5"
             style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0);
                    background-size: 36px 36px;">
        </div>
        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full bg-accent/10 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-primary-light/10 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-36 text-center">

            <!-- Badge -->
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-accent/20 text-accent
                         text-xs font-semibold uppercase tracking-widest mb-6 border border-accent/30">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Academia Pre-Universitaria #1 en el Perú
            </span>

            <!-- Título principal -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight max-w-4xl mx-auto">
                Tu ingreso a la<br>
                <span class="relative inline-block">
                    <span class="text-accent">universidad soñada</span>
                    <span class="absolute -bottom-1 left-0 w-full h-1 bg-accent/30 rounded-full"></span>
                </span>
                <br>empieza aquí
            </h1>

            <!-- Subtítulo -->
            <p class="mt-6 text-lg sm:text-xl text-white/70 max-w-2xl mx-auto leading-relaxed">
                Prepárate con los mejores docentes para los exámenes de admisión de las universidades
                más prestigiosas del Perú. Simulacros reales, temario completo y seguimiento personalizado.
            </p>

            <!-- Botones CTA -->
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#planes"
                   class="px-8 py-4 rounded-xl bg-accent text-white font-bold text-base
                          hover:bg-secondary transition-all duration-200 shadow-lg shadow-accent/30
                          hover:shadow-secondary/40 hover:-translate-y-0.5">
                    Ver planes de estudio
                </a>
                <a href="#contacto"
                   class="px-8 py-4 rounded-xl border-2 border-white/30 text-white font-bold text-base
                          hover:border-accent hover:text-accent transition-all duration-200">
                    Quiero ingresar →
                </a>
            </div>

            <!-- Stats -->
            <div class="mt-16 grid grid-cols-3 gap-6 max-w-lg mx-auto">
                <div class="text-center">
                    <div class="text-3xl font-black text-accent">3,500+</div>
                    <div class="text-white/60 text-xs mt-1">Ingresantes</div>
                </div>
                <div class="text-center border-x border-white/10">
                    <div class="text-3xl font-black text-accent">15+</div>
                    <div class="text-white/60 text-xs mt-1">Universidades</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black text-accent">98%</div>
                    <div class="text-white/60 text-xs mt-1">Aprobación</div>
                </div>
            </div>
        </div>

        <!-- Onda separadora -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 60L1440 60L1440 20C1440 20 1080 60 720 40C360 20 0 60 0 60Z" fill="#F2F2F2"/>
            </svg>
        </div>
    </section>

    {{-- ============================================================
         UNIVERSIDADES DESTINO
    ============================================================ --}}
    <section class="bg-brand-bg py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-xs font-semibold uppercase tracking-widest text-gray-400 mb-8">
                Nuestros alumnos ingresan a las mejores universidades del Perú
            </p>
            <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-7 gap-4">
                @foreach([
                    ['UNMSM', 'San Marcos'],
                    ['PUCP',  'Católica'],
                    ['UNI',   'UNI'],
                    ['UPCH',  'Cayetano'],
                    ['UNFV',  'Villarreal'],
                    ['UNALM', 'La Molina'],
                    ['USMP',  'San Martín'],
                ] as $uni)
                    <div class="flex flex-col items-center gap-2 p-4 bg-white rounded-2xl shadow-sm
                                hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                        <div class="w-10 h-10 rounded-xl bg-primary-dark/10 flex items-center justify-center
                                    group-hover:bg-primary-dark/20 transition-colors duration-200">
                            <span class="text-primary-dark font-black text-xs">{{ $uni[0] }}</span>
                        </div>
                        <span class="text-gray-500 text-[10px] font-medium text-center leading-tight">
                            {{ $uni[1] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         PLANES
    ============================================================ --}}
    <section id="planes" class="bg-white py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-14">
                <span class="inline-block px-4 py-1.5 rounded-full bg-accent/10 text-accent
                             text-xs font-semibold uppercase tracking-widest mb-3 border border-accent/20">
                    Planes de estudio
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-primary-dark">
                    Elige cómo prepararte
                </h2>
                <p class="mt-3 text-gray-500 max-w-xl mx-auto">
                    Temario completo de los exámenes de admisión más exigentes del país.
                    Paga una vez, ingresa a la universidad.
                </p>
            </div>

            @if($planes->isEmpty())
            <div class="text-center py-10 text-gray-400 text-sm">
                Próximamente publicaremos nuestros planes. ¡Contáctanos para más información!
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-{{ min($planes->count(), 3) }} gap-8 max-w-5xl mx-auto">
                @foreach($planes as $index => $plan)
                    <x-price-card
                        :plan="$plan->nombre"
                        :price="number_format($plan->precio, 0)"
                        :period="$plan->acceso_ilimitado ? 'Acceso ilimitado' : $plan->duracion_meses . ' ' . ($plan->duracion_meses == 1 ? 'mes' : 'meses')"
                        :badge="!$plan->esVip() && $plan->acceso_ilimitado ? 'Mejor opción' : (!$plan->esVip() && $index === 0 && $planes->count() > 1 ? 'Popular' : null)"
                        :featured="$plan->acceso_ilimitado && !$plan->esVip()"
                        :vip="$plan->esVip()"
                        :features="$plan->descripcion ? [$plan->descripcion] : []"
                    />
                @endforeach
            </div>
            @endif
        </div>
    </section>

    {{-- ============================================================
         POR QUÉ ELEGIRNOS
    ============================================================ --}}
    <section class="bg-brand-bg py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-14">
                <span class="inline-block px-4 py-1.5 rounded-full bg-primary-dark/10 text-primary-dark
                             text-xs font-semibold uppercase tracking-widest mb-3 border border-primary-dark/10">
                    Nuestras fortalezas
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-primary-dark">
                    ¿Por qué elegir Academia Nueva Era?
                </h2>
                <p class="mt-3 text-gray-500 max-w-xl mx-auto">
                    No solo enseñamos, te acompañamos hasta que logres ingresar.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">

                <!-- Docentes especializados -->
                <div class="flex flex-col items-center text-center p-8 rounded-2xl bg-white
                            hover:shadow-xl transition-shadow duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-accent/15 flex items-center justify-center mb-5
                                group-hover:bg-accent/25 transition-colors duration-300">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary-dark mb-2">Docentes especializados</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Profesores con amplia experiencia en los temarios y estilos de preguntas
                        de los principales exámenes de admisión del país.
                    </p>
                </div>

                <!-- Simulacros reales -->
                <div class="flex flex-col items-center text-center p-8 rounded-2xl bg-white
                            hover:shadow-xl transition-shadow duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-primary-light/15 flex items-center justify-center mb-5
                                group-hover:bg-primary-light/25 transition-colors duration-300">
                        <svg class="w-8 h-8 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary-dark mb-2">Simulacros reales</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Exámenes modelo basados en los últimos procesos de admisión de la UNMSM,
                        PUCP, UNI, UPCH y más. Practica en condiciones reales.
                    </p>
                </div>

                <!-- Alta tasa de ingreso -->
                <div class="flex flex-col items-center text-center p-8 rounded-2xl bg-white
                            hover:shadow-xl transition-shadow duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-secondary/15 flex items-center justify-center mb-5
                                group-hover:bg-secondary/25 transition-colors duration-300">
                        <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary-dark mb-2">Alta tasa de ingreso</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        El 98% de nuestros alumnos VIP logra ingresar en su primera o segunda
                        postulación. Resultados que respaldan nuestro método.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================================
         TESTIMONIOS
    ============================================================ --}}
    <section class="bg-primary-dark py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-black text-white">
                    Ellos ya lograron su sueño
                </h2>
                <p class="mt-3 text-white/60 max-w-xl mx-auto">
                    Miles de estudiantes ingresaron a la universidad gracias a Academia Nueva Era.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach([
                    ['Ingresé a la UNMSM en mi primer intento. Los simulacros me prepararon perfectamente para el examen real.',
                     'María G.', 'UNMSM — Medicina Humana', 'MG'],
                    ['El plan VIP valió cada sol. Los docentes de Matemática me explicaron como nunca antes lo habían hecho.',
                     'Carlos R.', 'PUCP — Ingeniería Civil', 'CR'],
                    ['Estudié 3 meses con el plan Premium y logré ingresar a la UNI. El ranking semanal me mantuvo enfocado.',
                     'Lucía T.', 'UNI — Ingeniería de Sistemas', 'LT'],
                ] as $t)
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition-colors duration-300">
                        <!-- Estrellas -->
                        <div class="flex gap-1 mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-white/80 text-sm leading-relaxed italic mb-4">
                            "{{ $t[0] }}"
                        </p>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-accent/20 flex items-center justify-center
                                        text-accent font-bold text-xs">
                                {{ $t[3] }}
                            </div>
                            <div>
                                <div class="text-white font-semibold text-sm">{{ $t[1] }}</div>
                                <div class="text-white/40 text-xs">{{ $t[2] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         FORMULARIO DE INTERÉS / CONTACTO
    ============================================================ --}}
    <section id="contacto" class="bg-primary-dark py-20 lg:py-28 relative overflow-hidden">
        {{-- Decoraciones --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-accent/10 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full bg-primary-light/10 blur-3xl"></div>
        </div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                {{-- Texto izquierdo --}}
                <div>
                    <span class="inline-block px-4 py-1.5 rounded-full bg-accent/20 text-accent
                                 text-xs font-semibold uppercase tracking-widest mb-4 border border-accent/30">
                        ¡Es gratis empezar!
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-white leading-tight mb-4">
                        ¿Te interesa prepararte?<br>
                        <span class="text-accent">Déjanos tus datos</span>
                    </h2>
                    <p class="text-white/60 text-base leading-relaxed mb-8">
                        Uno de nuestros asesores te contactará para contarte todo sobre nuestros planes,
                        horarios y cómo prepararte para ingresar a tu universidad.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-accent/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-white/70 text-sm">Te respondemos en menos de 24 horas</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-accent/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-white/70 text-sm">Sin compromisos ni costos ocultos</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-accent/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-white/70 text-sm">Te orientamos según tu universidad objetivo</p>
                        </div>
                    </div>
                </div>

                {{-- Formulario derecho --}}
                <div class="bg-white rounded-3xl p-7 shadow-2xl">

                    @if(session('lead_success'))
                    <div class="text-center py-8">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-primary-dark mb-2">¡Gracias por tu interés!</h3>
                        <p class="text-gray-500 text-sm">Uno de nuestros asesores te contactará muy pronto. ¡Prepárate para ingresar!</p>
                    </div>
                    @else

                    <h3 class="text-lg font-black text-primary-dark mb-1">Solicita información</h3>
                    <p class="text-gray-400 text-xs mb-5">Completa el formulario y nos ponemos en contacto contigo.</p>

                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-4">
                        <ul class="text-xs text-red-700 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('leads.store-public') }}" class="space-y-3">
                        @csrf

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Nombres <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}"
                                       placeholder="Juan Carlos"
                                       class="w-full px-3 py-2.5 rounded-xl border text-sm outline-none transition-all
                                              focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white
                                              {{ $errors->has('nombre') ? 'border-red-400' : 'border-gray-200' }}">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Apellidos <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="apellidos" value="{{ old('apellidos') }}"
                                       placeholder="García López"
                                       class="w-full px-3 py-2.5 rounded-xl border text-sm outline-none transition-all
                                              focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white
                                              {{ $errors->has('apellidos') ? 'border-red-400' : 'border-gray-200' }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                Correo electrónico <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       placeholder="correo@ejemplo.com"
                                       class="w-full pl-9 pr-3 py-2.5 rounded-xl border text-sm outline-none transition-all
                                              focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white
                                              {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                Teléfono / WhatsApp
                            </label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <input type="text" name="telefono" value="{{ old('telefono') }}"
                                       placeholder="987 654 321"
                                       class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 text-sm outline-none transition-all
                                              focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Nivel de interés
                                </label>
                                <select name="nivel"
                                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm outline-none transition-all
                                               focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white">
                                    <option value="no_sabe" {{ old('nivel') === 'no_sabe' ? 'selected' : '' }}>No sé aún</option>
                                    <option value="pollito" {{ old('nivel') === 'pollito' ? 'selected' : '' }}>🐣 Pollito (Escolar)</option>
                                    <option value="intermedio" {{ old('nivel') === 'intermedio' ? 'selected' : '' }}>⚡ Intermedio (Pre-U)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Plan de interés
                                </label>
                                <select name="plan_id"
                                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm outline-none transition-all
                                               focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white">
                                    <option value="">Sin preferencia</option>
                                    @foreach($planesContacto as $plan)
                                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                Mensaje <span class="text-gray-300 font-normal normal-case">(opcional)</span>
                            </label>
                            <textarea name="mensaje" rows="2"
                                      placeholder="¿Tienes alguna duda o pregunta específica?"
                                      class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm outline-none transition-all resize-none
                                             focus:border-accent focus:ring-2 focus:ring-accent/20 bg-gray-50 focus:bg-white">{{ old('mensaje') }}</textarea>
                        </div>

                        <button type="submit"
                                class="w-full py-3 rounded-xl font-black text-sm text-white
                                       bg-gradient-to-r from-primary-dark to-accent
                                       hover:from-accent hover:to-secondary
                                       transition-all duration-300 shadow-lg shadow-accent/30 hover:-translate-y-0.5">
                            Quiero que me contacten →
                        </button>

                        <p class="text-center text-xs text-gray-400">
                            Sin spam. Tus datos están seguros con nosotros.
                        </p>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         LLAMADA A LA ACCIÓN FINAL
    ============================================================ --}}
    <section class="bg-brand-bg py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Icono graduación -->
            <div class="w-16 h-16 rounded-2xl bg-primary-dark mx-auto flex items-center justify-center mb-6 shadow-lg">
                <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-primary-dark mb-4">
                ¿Listo para ingresar a tu universidad soñada?
            </h2>
            <p class="text-gray-500 text-lg mb-8 max-w-xl mx-auto">
                Únete a más de 3,500 estudiantes que ya lograron su ingreso con nosotros.
                Tu esfuerzo + nuestro método = resultado garantizado.
            </p>
            <a href="{{ route('register') }}"
               class="inline-block px-10 py-4 rounded-xl bg-accent text-white font-bold text-base
                      hover:bg-secondary transition-all duration-200 shadow-lg shadow-accent/30
                      hover:-translate-y-0.5">
                Comenzar mi preparación
            </a>
            <p class="text-gray-400 text-sm mt-4">
                Sin contratos. Sin letras pequeñas. Solo resultados.
            </p>
        </div>
    </section>

</x-layouts.landing>
