@php
$role = auth()->user()->role;

$aulaVirtualPatterns = ['cursos.*', 'clases.*', 'materiales.*', 'asistencias.*', 'alumno.mis-cursos', 'alumno.curso-detalle', 'alumno.asistencias'];
$aulaVirtualActiva   = collect($aulaVirtualPatterns)->contains(fn ($p) => request()->routeIs($p));

// ── Grupos de navegación ────────────────────────────────────────────────────
$navGroups = [
    [
        'label' => null,
        'items' => [
            [
                'label'     => 'Inicio',
                'route'     => 'dashboard',
                'roles'     => ['admin', 'docente', 'alumno', 'representante'],
                'available' => true,
                'icon'      => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            ],
        ],
    ],
    [
        'label' => 'Gestión',
        'roles' => ['admin'],
        'items' => [
            [
                'label'     => 'Alumnos',
                'route'     => 'alumnos.index',
                'pattern'   => 'alumnos.*',
                'roles'     => ['admin'],
                'available' => true,
                'icon'      => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
            ],
            [
                'label'     => 'Matrículas',
                'route'     => 'matriculas.index',
                'pattern'   => 'matriculas.*',
                'roles'     => ['admin'],
                'available' => true,
                'icon'      => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            ],
            [
                'label'     => 'Pagos',
                'route'     => 'pagos.index',
                'pattern'   => 'pagos.*',
                'roles'     => ['admin'],
                'available' => true,
                'icon'      => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            [
                'label'     => 'Planes',
                'route'     => 'planes.index',
                'pattern'   => 'planes.*',
                'roles'     => ['admin'],
                'available' => true,
                'icon'      => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            ],
            [
                'label'     => 'Prospectos',
                'route'     => 'leads.index',
                'pattern'   => 'leads.*',
                'roles'     => ['admin'],
                'available' => true,
                'icon'      => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
            ],
        ],
    ],
    [
        'label' => 'Sistema',
        'roles' => ['admin'],
        'items' => [
            [
                'label'     => 'Reportes',
                'route'     => 'dashboard.reportes',
                'pattern'   => 'dashboard.reportes',
                'roles'     => ['admin'],
                'available' => true,
                'icon'      => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
            ],
            [
                'label'     => 'Configuración',
                'route'     => 'dashboard.configuracion',
                'roles'     => ['admin'],
                'available' => true,
                'icon'      => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
            ],
            [
                'label'     => 'Bazar',
                'route'     => 'dashboard.bazar',
                'roles'     => ['admin'],
                'available' => false,
                'icon'      => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
            ],
            [
                'label'     => 'Reconocimientos',
                'route'     => 'dashboard.reconocimientos',
                'roles'     => ['admin'],
                'available' => false,
                'icon'      => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
            ],
        ],
    ],
];

// Sub-items del grupo Aula Virtual
$aulaVirtualItems = [
    ['label' => 'Cursos',          'route' => 'cursos.index',    'pattern' => 'cursos.*',           'roles' => ['admin', 'docente'], 'available' => true,  'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
    ['label' => 'Mis Cursos',      'route' => 'alumno.mis-cursos', 'pattern' => 'alumno.mis-cursos', 'roles' => ['alumno'],           'available' => true,  'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
    ['label' => 'Mis Asistencias', 'route' => 'alumno.asistencias', 'pattern' => 'alumno.asistencias', 'roles' => ['alumno'],        'available' => true,  'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
];

$aulaVirtualItemsFiltrados = array_filter($aulaVirtualItems, fn ($i) => in_array($role, $i['roles']));
$verGrupoAula = in_array($role, ['admin', 'docente', 'alumno']);

// Helper para filtrar items de un grupo por rol
$filtrarItems = fn($items) => array_filter($items, fn($i) => in_array($role, $i['roles']));
@endphp

<aside class="fixed inset-y-0 left-0 z-30 w-64 flex flex-col transition-transform duration-300 ease-in-out md:translate-x-0"
       :class="{ '-translate-x-full': !sidebarOpen }"
       style="background: linear-gradient(180deg, #071e3d 0%, #082B59 40%, #0a3266 100%)">

    {{-- ── Logo ──────────────────────────────────────────────────────────── --}}
    <div class="flex items-center gap-3 px-5 h-16 border-b border-white/10 flex-shrink-0">
        <div class="relative">
            <div class="absolute inset-0 bg-accent/20 rounded-xl blur-sm"></div>
            <img src="{{ asset('images/logo-academia.png') }}"
                 alt="Logo"
                 class="relative h-9 w-auto object-contain drop-shadow-lg">
        </div>
        <div class="leading-tight min-w-0">
            <p class="text-white font-bold text-sm leading-none">Academia</p>
            <p class="text-accent font-black text-sm leading-tight">Nueva Era Estudiantil</p>
        </div>
    </div>

    {{-- ── Perfil ────────────────────────────────────────────────────────── --}}
    <a href="{{ route('profile.edit') }}"
       class="mx-3 mt-3 mb-1 px-3 py-3 rounded-xl flex items-center gap-3 group
              bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20
              transition-all duration-200 cursor-pointer flex-shrink-0">

        <div class="relative flex-shrink-0">
            @if(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatarUrl() }}"
                     alt="Foto"
                     class="js-user-avatar w-9 h-9 rounded-lg object-cover ring-2 ring-accent/50
                            group-hover:ring-accent transition-all duration-200">
            @else
                <div class="js-user-avatar-placeholder w-9 h-9 rounded-lg bg-gradient-to-br
                            from-accent to-primary-light flex items-center justify-center
                            ring-2 ring-white/10 group-hover:ring-accent/50 transition-all duration-200">
                    <span class="text-white font-black text-sm uppercase">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </span>
                </div>
            @endif
            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-green-400 border-2
                        border-[#082B59]"></div>
        </div>

        <div class="min-w-0 flex-1">
            <p class="text-white text-sm font-semibold truncate leading-tight">
                {{ explode(' ', auth()->user()->name)[0] }}
            </p>
            <p class="text-white/40 text-xs capitalize truncate leading-tight">
                {{ auth()->user()->role }}
            </p>
        </div>

        <svg class="w-3.5 h-3.5 text-white/20 group-hover:text-white/50 flex-shrink-0 transition-colors"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    {{-- ── Navegación ────────────────────────────────────────────────────── --}}
    <nav class="flex-1 overflow-y-auto py-2 px-3 space-y-0.5 scrollbar-thin
                scrollbar-thumb-white/10 scrollbar-track-transparent">

        @foreach($navGroups as $group)
            @php
                // Filtrar items del grupo por rol
                $itemsVisibles = $filtrarItems($group['items']);
                $tieneItems = !empty($itemsVisibles);
                $esGrupoAdmin = isset($group['roles']) && !in_array($role, $group['roles']);
            @endphp

            @if(!$tieneItems || $esGrupoAdmin)
                @continue
            @endif

            {{-- Insertar Aula Virtual antes del grupo "Sistema" --}}
            @if(isset($group['label']) && $group['label'] === 'Sistema' && $verGrupoAula)
                <div class="px-3 pt-4 pb-1">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-white/25">Aula Virtual</p>
                </div>
                @include('components.sidebar-aula-group')
            @endif

            {{-- Separador con etiqueta --}}
            @if($group['label'])
                <div class="px-3 pt-4 pb-1">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-white/25">
                        {{ $group['label'] }}
                    </p>
                </div>
            @elseif(!$loop->first)
                <div class="my-1 mx-2 border-t border-white/10"></div>
            @endif

            @foreach($itemsVisibles as $item)
                @php
                    $isActive = request()->routeIs($item['route'])
                        || (isset($item['pattern']) && request()->routeIs($item['pattern']));
                @endphp

                @if($item['available'])
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 group',
                           'bg-accent/20 text-accent shadow-sm shadow-accent/10'                     => $isActive,
                           'text-white/60 hover:bg-white/10 hover:text-white'                         => !$isActive,
                       ])>
                        <div @class([
                                 'w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200',
                                 'bg-accent/20'                                         => $isActive,
                                 'bg-white/5 group-hover:bg-white/10'                   => !$isActive,
                             ])>
                            <svg class="w-4.5 h-4.5 {{ $isActive ? 'text-accent' : 'text-white/50 group-hover:text-white/80' }}"
                                 style="width:1.125rem;height:1.125rem"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="flex-1 truncate">{{ $item['label'] }}</span>
                        @if($isActive)
                            <span class="w-1.5 h-1.5 rounded-full bg-accent flex-shrink-0"></span>
                        @endif
                    </a>
                @else
                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                                opacity-40 cursor-not-allowed select-none">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 bg-white/5">
                            <svg style="width:1.125rem;height:1.125rem" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24" class="text-white/40">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="flex-1 truncate text-white/40">{{ $item['label'] }}</span>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full
                                     bg-white/10 text-white/30 uppercase tracking-wide flex-shrink-0">
                            Pronto
                        </span>
                    </div>
                @endif
            @endforeach
        @endforeach

        {{-- Aula Virtual al final para roles sin grupo Sistema (alumno) --}}
        @if($verGrupoAula && !in_array($role, ['admin', 'docente']))
            @include('components.sidebar-aula-group')
        @endif

    </nav>

    {{-- ── Cerrar sesión ────────────────────────────────────────────────── --}}
    <div class="px-3 pb-4 pt-2 border-t border-white/10 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                           text-white/40 hover:bg-red-500/10 hover:text-red-400
                           transition-all duration-150 group">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                            bg-white/5 group-hover:bg-red-500/10 transition-colors">
                    <svg style="width:1.125rem;height:1.125rem" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" class="text-white/30 group-hover:text-red-400 transition-colors">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <span>Cerrar sesión</span>
            </button>
        </form>
    </div>

</aside>
