<?php

use Livewire\Volt\Component;

new class extends Component {
    public $seriesGenres = [];

    public function mount()
    {
        $this->loadSeriesGenres();
    }

    public function loadSeriesGenres()
    {
        $this->seriesGenres = Cache::remember('series_genres', 3600, function () {
            return Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/genre/tv/list')->json()['genres'];
        });
    }
}; ?>

<div class="inline-flex">
    <x-dropdown align="left" width="96" class="bg-black w-96 bg-opacity-80">
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
            <div class="grid grid-cols-3 gap-1 p-1">
                @foreach ($seriesGenres as $genre)
                    <x-dropdown-link class="hover:cursor-pointer" wire:navigate>
                        {{ $genre['name'] }}
                    </x-dropdown-link>
                @endforeach
            </div>
        </x-slot>
    </x-dropdown>
</div>
