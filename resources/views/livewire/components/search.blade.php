<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

new class extends Component {
    public $query = '';
    public $allCatalog = [];
    public $page = 1;

    public function mount()
    {
        $this->loadAllCatalog();
    }

    private function fetchMovies()
    {
        $movies =
            Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/search/movie?query={$this->query}&page={$this->page}&limit=10")
                ->json()['results'] ?? [];

        return $movies;
    }

    private function fetchTVShows()
    {
        $series =
            Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/discover/tv?api_key=' . config('services.tmdb.token') . "&page={$this->page}&limit=10")
                ->json()['results'] ?? [];

        return $series;
    }

    public function loadAllCatalog()
    {
        $this->allCatalog = Cache::remember('all_catalog', 3600, function () {
            $movies = $this->fetchMovies();
            $tvShows = $this->fetchTVShows();

            return collect($movies)->merge($tvShows)->shuffle()->values();
        });
    }

    public function updatedQuery()
    {
        $this->search();
    }

    public function search()
    {
        if (empty($this->query)) {
            $this->loadAllCatalog();
        } else {
            $this->allCatalog = Cache::remember("search_{$this->query}", 300, function () {
                $movies =
                    Http::withToken(config('services.tmdb.token'))
                        ->get("https://api.themoviedb.org/3/search/movie?query={$this->query}")
                        ->json()['results'] ?? [];

                $tvShows =
                    Http::withToken(config('services.tmdb.token'))
                        ->get("https://api.themoviedb.org/3/search/tv?query={$this->query}")
                        ->json()['results'] ?? [];

                return collect($movies)->merge($tvShows)->shuffle()->values();
            });
        }
    }
}; ?>

<div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="relative">
        <x-text-input wire:model.live.debounce.300ms="query" class="w-full pl-12 font-bold text-white"
            placeholder="Search"></x-text-input>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
            class="absolute text-white transform -translate-y-1/2 left-3 top-1/2 size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-10 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
        @foreach ($allCatalog as $index => $item)
            @if (isset($item['title']))
                <x-movie-card :movie="$item" :index="$index" />
            @elseif (isset($item['name']))
                <x-series-card :series="$item" :index="$index" />
            @endif
        @endforeach
    </div>
</div>
