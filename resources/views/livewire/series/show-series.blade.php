<?php

use Livewire\Volt\Component;

new class extends Component {
    public $series;
    public $image;
    public $trailerUrl;
    public $seriesGenres = [];
    public $cast = [];
    public $episodes = [];
    public $selectedSeason = 1;

    public function mount($id)
    {
        $this->loadSeriesGenres();
        $this->loadSeriesData($id);
        $this->loadSeriesTrailer($id);
        $this->loadSeriesCast($id);
        $this->loadSeasonEpisodes(1);
    }

    protected function loadSeriesCast($id)
    {
        $this->cast = Cache::remember("series_{$id}_cast", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/tv/{$id}/credits")
                ->json();
        });
    }

    protected function loadSeriesTrailer($id)
    {
        $trailers = Cache::remember("series_{$id}_trailers", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/tv/{$id}/videos")
                ->json();
        });

        if (!empty($trailers['results']) && count($trailers['results']) > 0) {
            $randomTrailer = collect($trailers['results'])->random();
            $this->trailerUrl = "https://www.youtube.com/embed/{$randomTrailer['key']}";
        } else {
            $this->trailerUrl = null;
        }
    }

    protected function loadSeriesData($id)
    {
        $this->series = Cache::remember("series_{$id}", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/tv/{$id}")
                ->json();
        });
    }

    public function loadSeasonEpisodes($seasonNumber)
    {
        $this->selectedSeason = $seasonNumber;
        $this->episodes = Cache::remember("series_{$this->series['id']}_season_{$seasonNumber}_episodes", 3600, function () use ($seasonNumber) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/tv/{$this->series['id']}/season/{$seasonNumber}")
                ->json()['episodes'] ?? [];
        });
        $this->dispatch('livewireFetchedData');
    }

    protected function loadSeriesGenres()
    {
        $genresArray = Cache::remember('series_genres', 3600, function () {
            return Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/genre/tv/list')->json()['genres'] ?? [];
        });

        $genres = collect($genresArray)->mapWithKeys(fn($genre) => [$genre['id'] => $genre['name']]);
        $this->seriesGenres = $genres;
    }
}; ?>

<div class="bg-black">
    <div class="z-30 w-full min-h-[70vh] md:min-h-[75vh] mx-auto max-w-7xl">
        <img src="https://image.tmdb.org/t/p/original{{ $series['backdrop_path'] ?? '' }}"
            class="absolute top-0 left-0 object-cover w-full md:h-full h-[80vh]" alt="{{ $series['name'] }}">
        <div class="absolute inset-0 md:h-full h-[80vh] bg-gradient-to-b from-black via-transparent to-black"></div>

        <!-- Title and description -->
        <div class="flex items-end justify-start md:min-h-[75vh] min-h-[70vh]">
            <div class="z-10 p-4 text-white">
                <div class="space-y-2">
                    <h2 class="text-5xl font-medium md:font-bold md:text-7xl">{{ $series['name'] }}</h2>

                    <div class="flex items-center gap-3 text-xs text-gray-300 md:text-lg">
                        <p>{{ $series['adult'] ? '18+' : '13+' }}</p>
                        <p>{{ $series['number_of_seasons'] }} seasons</p>
                        <p>{{ $firstAirDate = \Carbon\Carbon::parse($series['first_air_date'])->year }}</p>
                    </div>

                    <div class="pt-2">
                        <x-primary-button class="px-16">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="text-black size-4 md:size-6">
                                    <path fill-rule="evenodd"
                                        d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-xs font-bold text-black md:text-md">Watch now</p>
                            </div>
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative h-full mx-auto max-w-7xl">
        <div class="max-w-4xl px-4 pb-8 space-y-1 text-gray-300">
            <!-- Add to watchlist -->
            <div class="mt-4" x-cloak>
                <livewire:components.toggle-watchlist-on-header :itemType="'series'" :itemId="$series['id']" />
            </div>

            <!-- Overview -->
            @if ($series['overview'])
                <p class="text-sm text-gray-300 md:text-base">{{ $series['overview'] }}</p>
            @endif

            <!-- Genres -->
            <p class="text-sm text-gray-400">
                @foreach ($series['genres'] as $genre)
                    <a class="hover:cursor-pointer hover:underline" href="{{ route('series.genres', $genre['id']) }}"
                        wire:navigate>{{ $seriesGenres[$genre['id']] ?? '' }}</a>@if(!$loop->last),@endif
                @endforeach
            </p>

            <!-- Cast -->
            @if ($this->cast)
                <p class="text-xs text-gray-500">
                    @foreach (array_slice($this->cast['cast'], 0, 5) as $actor)
                        {{ $actor['name'] }}@if(!$loop->last),@endif
                    @endforeach
                </p>
            @endif
        </div>

        @if ($this->selectedSeason > 0)
            <!-- Seasons -->
            <div class="px-4 mt-6 mb-0">
                <div class="inline-flex">
                    <x-dropdown align="left" width="48" class="bg-black bg-opacity-80">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-400 transition duration-150 ease-in-out bg-transparent border border-transparent rounded-md hover:text-gray-300 focus:outline-none">
                                <div x-text="'Season {{ $this->selectedSeason }}'"></div>

                                <div class="ms-1">
                                    <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @foreach ($series['seasons'] as $season)
                                <x-dropdown-link wire:click="loadSeasonEpisodes({{ $season['season_number'] }})"
                                    class="px-4 py-2 text-gray-100 rounded">
                                    {{ $season['name'] }}
                                </x-dropdown-link>
                            @endforeach
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Episode list -->
                <div class="z-30 mt-6 overflow-hidden episode-swiper" id="episode-swiper">
                    @if (count($episodes) > 0)
                        <div class="swiper-wrapper">
                            @foreach ($episodes as $episode)
                                <div class="swiper-slide">
                                    <x-episode-card :episode="$episode" :series="$series" :selectedSeason="$selectedSeason" />
                                </div>
                            @endforeach
                        </div>

                        <div class="relative z-30 swiper-button-prev"></div>
                        <div class="relative z-10 swiper-button-next"></div>
                    @else
                        <p class="px-3 text-sm text-gray-400">No episodes available.</p>
                    @endif
                </div>
            </div>
        @endif
        
        <!-- Trailer -->
        @if ($trailerUrl)
            <div class="mx-auto mt-10 max-w-7xl">
                <div class="w-auto h-auto">
                    <iframe src="{{ $trailerUrl }}?autoplay=1" title="{{ $series['name'] }} video" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen class="w-full rounded-md h-[100vh]"></iframe>
                </div>
            </div>
        @endif

        <div class="px-6 py-10">
            <livewire:series.recommendations :id="$series['id']" />
        </div>
    </div>
</div>
