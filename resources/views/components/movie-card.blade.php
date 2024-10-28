@props(['movie', 'index'])

<div x-data="{ loaded: false }" class="relative overflow-hidden transition duration-300 transform bg-gray-100 rounded-sm shadow-md dark:bg-gray-800 hover:shadow-xl hover:scale-105">
    <div class="absolute inset-0 flex items-center justify-center" x-show="!loaded">
        <div class="w-8 h-8 border-4 border-gray-200 rounded-full border-t-gray-500 animate-spin"></div>
    </div>
    
    <img 
        loading="{{ $index < 6 ? 'eager' : 'lazy' }}" 
        src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
        alt="{{ $movie['title'] }}" 
        class="object-cover w-full transition duration-500 ease-in-out opacity-0 h-80"
        @load="loaded = true"
        :class="loaded ? 'opacity-100' : 'opacity-0'"
    >
</div>