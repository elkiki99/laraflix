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

<div class="inline-flex pt-2">
    <x-dropdown align="right" width="64" class="bg-black bg-opacity-80">
        <x-slot name="trigger">
            <button
                class="inline-flex items-center text-sm font-medium leading-4 text-gray-400 transition duration-150 ease-in-out bg-transparent border border-transparent rounded-md hover:text-gray-300 focus:outline-none">
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
            <div class="grid grid-cols-2 gap-1 sm:grid-cols-3">
                @foreach ($movieGenres as $genre)
                    <x-dropdown-link class="text-xs sm:text-sm" href="{{ route('movies.genres', $genre['id'])}}" wire:navigate>
                        {{ $genre['name'] }}
                    </x-dropdown-link>
                @endforeach 
            </div>
        </x-slot>
    </x-dropdown>
</div>
