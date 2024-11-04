<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>

<body class="font-sans antialiased">
    <div class="flex flex-col min-h-screen bg-gray-950">

        <div class="relative z-50">
            <livewire:layout.navigation class="bg-transparent" />
        </div>

        @if (request()->routeIs('home'))
            <livewire:layout.home-header />
        @endif

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        @if (request()->is('movie/*') || (request()->is('series/*') && !request()->is('series')))
            <livewire:layout.footer class="bg-black" />
        @else
            <livewire:layout.footer class="bg-gray-950" />
        @endif
    </div>
</body>

</html>
