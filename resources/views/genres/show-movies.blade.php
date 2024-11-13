<x-app-layout>
    <livewire:layout.movie-genre-header :genreId="$genreId" />
    
    <div class="px-4 py-4 sm:px-6 lg:px-8">
        <livewire:genres.show-movie-genre :genreId="$genreId" />
    </div>
</x-app-layout>
