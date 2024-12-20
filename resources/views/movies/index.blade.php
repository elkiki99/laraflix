<x-app-layout>
    <livewire:layout.movies-header />

    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Genres -->
        <div class="flex justify-end px-4">
            <livewire:genres.movie-genres />
        </div>
        
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