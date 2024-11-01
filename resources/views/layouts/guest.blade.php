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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="relative flex items-center justify-center min-h-screen py-5 font-sans antialiased text-gray-900 bg-center bg-cover"
    style="background-image: url('{{ asset('storage/bg-guest.jpg') }}');">
    
    <div class="absolute inset-0 bg-black opacity-30"></div>
    <div class="absolute inset-0 backdrop-blur-sm"></div>

    <div class="relative z-10 flex flex-col items-center min-h-screen pt-6 bg-transparent sm:justify-center sm:pt-0">
        
        <div class="relative z-20 pb-10">
            <a href="/" wire:navigate>
                <x-application-logo class="w-20 h-20 text-gray-200 fill-current" />
            </a>
        </div>

        <div class="relative z-20 p-8 text-gray-200 bg-black rounded-lg md:w-[400px] shadow-lg bg-opacity-80">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
