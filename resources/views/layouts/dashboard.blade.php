<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brand-bg font-sans antialiased">

<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    {{-- Overlay oscuro (solo móvil) --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-20 bg-black/50 md:hidden"
         style="display: none;">
    </div>

    {{-- Sidebar --}}
    <x-sidebar />

    {{-- Spacer desktop: reserva el espacio del sidebar (w-64) en el flujo --}}
    {{-- safelist: -translate-x-full --}}
    <div class="hidden md:block w-64 flex-shrink-0"></div>

    {{-- Área principal --}}
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

        {{-- Topbar móvil --}}
        <header class="md:hidden flex items-center h-14 px-4 bg-white border-b border-gray-200 shadow-sm flex-shrink-0 gap-3">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <img src="{{ asset('images/logo-academia.png') }}" alt="Logo" class="h-7 w-auto object-contain">
            <span class="text-sm font-bold text-primary-dark truncate">{{ config('app.name') }}</span>
        </header>

        {{-- Contenido de página --}}
        <main class="flex-1 overflow-y-auto p-4 md:p-6">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
