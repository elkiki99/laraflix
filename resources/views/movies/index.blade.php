<x-app-layout>
    <div class="min-h-screen px-6 py-10 dark:text-white dark:bg-gray-900">
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