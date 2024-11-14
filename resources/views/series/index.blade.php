<x-app-layout>
    <livewire:layout.series-header />

    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Genres -->
        <div class="flex justify-end px-4">
            <livewire:genres.series-genres />
        </div>

        <!-- Airing today -->
        <livewire:series.airing-today />

        <!-- Top rated -->
        <livewire:series.top-rated />

        <!-- On the air -->
        <livewire:series.on-the-air />

        <!-- Popular -->
        <livewire:series.popular />
    </div>
</x-app-layout>
