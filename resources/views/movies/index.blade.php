<x-app-layout>
    <div class="min-h-screen px-6 py-10 text-white">
        <!-- Genres -->
        <livewire:genres.movie-genres />
        
        <!-- Popular -->
        <livewire:movies.popular />

        <!-- Top rated -->
        <livewire:movies.top-rated />
        
        <!-- Upcoming -->
        <livewire:movies.upcoming />

        <!-- Now playing -->
        <livewire:movies.now-playing />
    </div>
</x-app-layout>