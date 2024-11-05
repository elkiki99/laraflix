<x-app-layout>
    <livewire:layout.series-genre-header :genreId="$genreId" />
    
    <div class="px-4 sm:px-6 lg:px-8">
        <livewire:genres.show-series-genre :genreId="$genreId" />
    </div>
</x-app-layout>
