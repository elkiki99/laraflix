<x-app-layout>
    <div class="min-h-screen px-6 py-10 text-white bg-gray-900">
        {{-- <livewire:movies.trending /> --}}
        <livewire:movies.popular />
        <livewire:movies.now-playing />
        <livewire:movies.upcoming />
        <livewire:movies.top-rated />
        <livewire:series.airing-today />
        <livewire:series.top-rated />
        <livewire:series.popular />
        <livewire:series.on-the-air />
    </div>
</x-app-layout>
