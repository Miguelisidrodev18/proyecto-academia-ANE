@props(['title' => 'Academia Nueva Era'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-background font-sans antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo centrado -->
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo-academia.png') }}" 
                     alt="Academia Nueva Era" 
                     class="h-20 w-auto mx-auto mb-4">
                <h1 class="text-3xl font-black text-primary-dark">Academia Nueva Era</h1>
                <p class="text-sm text-gray-500 mt-1">Excelencia académica</p>
            </div>

            <!-- Tarjeta de contenido -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                {{ $slot }}
            </div>

            <!-- Footer simple -->
            <p class="text-center text-xs text-gray-400 mt-8">
                &copy; {{ date('Y') }} Academia Nueva Era. Todos los derechos reservados.
            </p>
        </div>
    </div>
    @vite('resources/js/app.js')
</body>
</html>