<?php

use Livewire\Volt\Component;

new class extends Component {
    public $movieGenres = [];

    public function mount()
    {
        $this->loadMovieGenres();
    }

    public function loadMovieGenres()
    {
        $this->movieGenres = Cache::remember('movie_genres', 3600, function () {
            return Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/genre/movie/list')->json()['genres'];
        });
    }
}; ?>

<x-dropdown align="left" width="72">
    <x-slot name="trigger">
        <button
            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-transparent border border-transparent rounded-md dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
            <div>Genres</div>

            <div class="ms-1">
                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </button>
    </x-slot>

    <x-slot name="content">
        <div class="grid grid-cols-3 gap-2 p-2">
            @foreach ($movieGenres as $genre)
                <x-dropdown-link :href="route('profile')" wire:navigate>
                    {{ $genre['name'] }}
                </x-dropdown-link>
            @endforeach
        </div>
    </x-slot>
</x-dropdown>
