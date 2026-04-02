@php
$role = auth()->user()->role;

// Rutas que pertenecen al grupo Aula Virtual (para detectar si el grupo está activo)
$aulaVirtualPatterns = ['cursos.*', 'clases.*', 'materiales.*', 'asistencias.*', 'alumno.mis-cursos', 'alumno.curso-detalle', 'alumno.asistencias'];
$aulaVirtualActiva   = collect($aulaVirtualPatterns)->contains(fn ($p) => request()->routeIs($p));

$nav = [
    // ── Todos los roles ──────────────────────────────────────────────────────
    [
        'label'     => 'Inicio',
        'route'     => 'dashboard',
        'roles'     => ['admin', 'docente', 'alumno', 'representante'],
        'available' => true,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
    ],

    // ── Solo Admin ───────────────────────────────────────────────────────────
    [
        'label'     => 'Alumnos',
        'route'     => 'alumnos.index',
        'pattern'   => 'alumnos.*',
        'roles'     => ['admin'],
        'available' => true,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
    ],
    [
        'label'     => 'Matrículas',
        'route'     => 'matriculas.index',
        'pattern'   => 'matriculas.*',
        'roles'     => ['admin'],
        'available' => true,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
    ],
    [
        'label'     => 'Pagos',
        'route'     => 'pagos.index',
        'pattern'   => 'pagos.*',
        'roles'     => ['admin'],
        'available' => true,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ],
    [
        'label'     => 'Planes',
        'route'     => 'planes.index',
        'pattern'   => 'planes.*',
        'roles'     => ['admin'],
        'available' => true,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
    ],

    // ── CRM ──────────────────────────────────────────────────────────────────
    [
        'label'     => 'Prospectos',
        'route'     => 'leads.index',
        'pattern'   => 'leads.*',
        'roles'     => ['admin'],
        'available' => true,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>',
    ],

    // ── Gestión (Admin) ───────────────────────────────────────────────────────
    [
        'label'     => 'Bazar',
        'route'     => 'dashboard.bazar',
        'roles'     => ['admin'],
        'available' => false,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',
    ],
    [
        'label'     => 'Reconocimientos',
        'route'     => 'dashboard.reconocimientos',
        'roles'     => ['admin'],
        'available' => false,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
    ],
    [
        'label'     => 'Reportes',
        'route'     => 'dashboard.reportes',
        'roles'     => ['admin'],
        'available' => false,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
    ],
    [
        'label'     => 'Configuración',
        'route'     => 'dashboard.configuracion',
        'roles'     => ['admin'],
        'available' => false,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
    ],
];

// Sub-items del grupo Aula Virtual por rol
$aulaVirtualItems = [
    // Admin + Docente
    [
        'label'     => 'Cursos',
        'route'     => 'cursos.index',
        'pattern'   => 'cursos.*',
        'roles'     => ['admin', 'docente'],
        'available' => true,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>',
    ],
    // Solo Alumno
    [
        'label'     => 'Mis Cursos',
        'route'     => 'alumno.mis-cursos',
        'pattern'   => 'alumno.mis-cursos',
        'roles'     => ['alumno'],
        'available' => true,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
    ],
    [
        'label'     => 'Mis Asistencias',
        'route'     => 'alumno.asistencias',
        'pattern'   => 'alumno.asistencias',
        'roles'     => ['alumno'],
        'available' => true,
        'icon'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
    ],
];

// Filtrar items del grupo por rol
$aulaVirtualItemsFiltrados = array_filter($aulaVirtualItems, fn ($i) => in_array($role, $i['roles']));

// Filtrar nav principal por rol
$navFiltrado = array_filter($nav, fn ($item) => in_array($role, $item['roles']));

// Roles que tienen acceso al grupo Aula Virtual
$verGrupoAula = in_array($role, ['admin', 'docente', 'alumno']);
@endphp

<aside class="fixed inset-y-0 left-0 z-30 w-64 bg-primary-dark flex flex-col
              transition-transform duration-300 ease-in-out md:translate-x-0"
       :class="{ '-translate-x-full': !sidebarOpen }">

    {{-- Logo + nombre --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10 flex-shrink-0">
        <img src="{{ asset('images/logo-academia.png') }}"
             alt="Logo Academia"
             class="h-10 w-auto object-contain drop-shadow">
        <div class="leading-tight">
            <p class="text-white font-bold text-sm leading-none">Academia</p>
            <p class="text-[#0BC4D9] font-black text-sm leading-none">Nueva Era Estudiantil</p>
        </div>
    </div>

    {{-- Perfil del usuario --}}
    <a href="{{ route('profile.edit') }}"
       class="px-5 py-4 border-b border-white/10 flex-shrink-0 flex items-center gap-3 group
              hover:bg-white/5 transition-colors duration-150 cursor-pointer">

        {{-- Avatar: foto real o inicial --}}
        <div class="relative flex-shrink-0">
            @if(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatarUrl() }}"
                     alt="Foto de perfil"
                     class="js-user-avatar w-10 h-10 rounded-full object-cover ring-2 ring-[#0BC4D9]/60
                            group-hover:ring-[#0BC4D9] transition-all duration-200 shadow-lg">
            @else
                <div class="js-user-avatar-placeholder w-10 h-10 rounded-full bg-gradient-to-br
                            from-[#0BC4D9] to-[#30A9D9] flex items-center justify-center
                            ring-2 ring-white/20 group-hover:ring-[#0BC4D9]/60
                            transition-all duration-200 shadow-lg">
                    <span class="text-white font-black text-sm uppercase">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </span>
                </div>
            @endif

            {{-- Indicador de edición al hover --}}
            <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full
                        bg-[#0BC4D9] border-2 border-primary-dark flex items-center justify-center
                        opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-2 h-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </div>
        </div>

        <div class="min-w-0 flex-1">
            <p class="text-white font-semibold text-sm truncate group-hover:text-[#0BC4D9] transition-colors">
                {{ auth()->user()->name }}
            </p>
            <span class="inline-block text-xs px-2 py-0.5 rounded-full font-medium capitalize
                         bg-white/10 text-[#0BC4D9]">
                {{ auth()->user()->role }}
            </span>
        </div>

        {{-- Chevron --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/30 group-hover:text-white/60 flex-shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    {{-- Navegación --}}
    <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-0.5 scrollbar-thin">

        {{-- Inicio --}}
        @foreach($navFiltrado as $item)
            @php
                $isActive = request()->routeIs($item['route'])
                    || (isset($item['pattern']) && request()->routeIs($item['pattern']));
            @endphp

            {{-- Insertar grupo Aula Virtual antes de Bazar --}}
            @if($item['route'] === 'dashboard.bazar' && $verGrupoAula)
                @include('components.sidebar-aula-group')
            @endif

            <a href="{{ route($item['route']) }}"
               @class([
                   'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 group relative',
                   'bg-[#0BC4D9]/15 text-[#0BC4D9] border-l-2 border-[#0BC4D9]'           => $isActive,
                   'text-white/70 hover:bg-white/5 hover:text-white border-l-2 border-transparent' => !$isActive,
               ])>
                <svg class="w-5 h-5 flex-shrink-0 {{ $isActive ? 'text-[#0BC4D9]' : 'text-white/50 group-hover:text-white/80' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $item['icon'] !!}
                </svg>
                <span class="flex-1 truncate">{{ $item['label'] }}</span>
                @if(!$item['available'])
                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full
                                 bg-white/10 text-white/40 uppercase tracking-wide flex-shrink-0">
                        Pronto
                    </span>
                @endif
            </a>
        @endforeach

        {{-- Si no hay ítems tras el grupo (ej: alumno/representante), renderizar el grupo al final --}}
        @if($verGrupoAula && !collect($navFiltrado)->contains('route', 'dashboard.bazar'))
            @include('components.sidebar-aula-group')
        @endif

    </nav>

    {{-- Logout --}}
    <div class="px-3 py-3 border-t border-white/10 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                           text-white/60 hover:bg-white/5 hover:text-white
                           transition-all duration-150 group border-l-2 border-transparent">
                <svg class="w-5 h-5 flex-shrink-0 text-white/40 group-hover:text-white/70"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Cerrar sesión</span>
            </button>
        </form>
    </div>

</aside>
