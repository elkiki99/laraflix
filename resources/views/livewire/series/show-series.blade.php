<?php

// use Livewire\Volt\Component;

// new class extends Component {
//     public $series;
//     public $image;
//     public $trailerUrl;
//     public $seriesGenres = [];
//     public $cast = [];
//     public $episodes = [];
//     public $selectedSeason = 1;

//     public function mount($id)
//     {
//         $this->loadSeriesGenres();
//         $this->loadSeriesData($id);
//         $this->loadSeriesTrailer($id);
//         $this->loadSeriesCast($id);
//         $this->loadSeriesDetails($id);
//         $this->loadSeasonEpisodes($this->selectedSeason);
//     }

//     public function loadSeriesCast($id)
//     {
//         $this->cast = Cache::remember("series_{$id}_cast", 3600, function () use ($id) {
//             return Http::withToken(config('services.tmdb.token'))
//                 ->get("https://api.themoviedb.org/3/tv/{$id}/credits")
//                 ->json();
//         });
//     }

//     public function loadSeriesTrailer($id)
//     {
//         $trailers = Cache::remember("series_{$id}_trailers", 3600, function () use ($id) {
//             return Http::withToken(config('services.tmdb.token'))
//                 ->get("https://api.themoviedb.org/3/tv/{$id}/videos")
//                 ->json();
//         });

//         if (!empty($trailers['results']) && count($trailers['results']) > 0) {
//             $randomTrailer = collect($trailers['results'])->random();
//             $this->trailerUrl = "https://www.youtube.com/embed/{$randomTrailer['key']}";
//         } else {
//             $this->trailerUrl = null;
//         }
//     }

//     public function loadSeriesData($id)
//     {
//         $this->series = Cache::remember("series_{$id}", 3600, function () use ($id) {
//             return Http::withToken(config('services.tmdb.token'))
//                 ->get("https://api.themoviedb.org/3/tv/{$id}")
//                 ->json();
//         });
//     }

//     public function loadSeriesDetails($id)
//     {
//         $this->series = Cache::remember("series_{$id}_details", 3600, function () use ($id) {
//             return Http::withToken(config('services.tmdb.token'))
//                 ->get("https://api.themoviedb.org/3/tv/{$id}")
//                 ->json();
//         });
//     }

//     public function loadSeasonEpisodes($seasonNumber)
//     {
//         $this->selectedSeason = $seasonNumber;
//         $this->episodes = Cache::remember("series_{$this->series['id']}_season_{$seasonNumber}_episodes", 3600, function () use ($seasonNumber) {
//             return Http::withToken(config('services.tmdb.token'))
//                 ->get("https://api.themoviedb.org/3/tv/{$this->series['id']}/season/{$seasonNumber}")
//                 ->json()['episodes'] ?? [];
//         });
//         $this->dispatch('livewireFetchedData');
//     }

//     public function loadSeriesGenres()
//     {
//         $genresArray = Cache::remember('series_genres', 3600, function () {
//             return Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/genre/tv/list')->json()['genres'] ?? [];
//         });

//         $genres = collect($genresArray)->mapWithKeys(fn($genre) => [$genre['id'] => $genre['name']]);
//         $this->seriesGenres = $genres;
//     }
// }; ?>

{{-- // <div class="bg-black">
//     <div class="z-30 w-full h-[90vh] mx-auto max-w-7xl">
//         <img src="https://image.tmdb.org/t/p/original{{ $series['backdrop_path'] ?? '' }}"
//             class="absolute top-0 left-0 object-cover w-full h-full" alt="{{ $series['name'] }}">
//         <div class="absolute inset-0 bg-gradient-to-b from-black via-transparent to-black"></div>

//         <div class="flex items-end justify-start h-[80vh]">
//             <div class="relative p-4 text-white">
//                 <div class="space-y-2">
//                     <h2 class="font-bold text-7xl">{{ $series['name'] }}</h2>

//                     <div class="flex items-center gap-3 text-gray-400">
//                         <p>{{ $series['adult'] ? '18+' : '13+' }}</p>
//                         <p>{{ $series['number_of_seasons'] }} seasons</p>
//                         <p>{{ $firstAirDate = \Carbon\Carbon::parse($series['first_air_date'])->year }}</p>
//                     </div>

//                     <x-primary-button class="absolute px-16">
//                         <div class="flex items-center gap-2">
//                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
//                                 class="text-black size-6">
//                                 <path fill-rule="evenodd"
//                                     d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
//                                     clip-rule="evenodd" />
//                             </svg>
//                             <p class="font-bold text-black">Watch now</p>
//                         </div>
//                     </x-primary-button>
//                 </div>
//             </div>
//         </div>
//     </div>

//     <div class="relative h-full pb-10 mx-auto bg-black max-w-7xl">
//         <div class="max-w-4xl px-4 pb-8 text-gray-300">
//             <!-- Overview -->
//             @if ($series['overview'])
//                 <p>{{ $series['overview'] }}</p>
//             @endif

//             <!-- Cast -->
//             @if ($this->cast)
//                 <p class="py-2 text-sm text-gray-400">
//                     @foreach (array_slice($this->cast['cast'], 0, 5) as $actor)
//                         {{ $actor['name'] }}@if (!$loop->last)
//                             ,
//                         @endif
//                     @endforeach
//                 </p>
//             @endif

//             <!-- Genre -->
//             <p class="py-2 text-sm text-gray-500">
//                 @foreach ($series['genres'] as $genre)
//                     {{ $seriesGenres[$genre['id']] ?? '' }}@if (!$loop->last)
//                         ,
//                     @endif
//                 @endforeach
//             </p>
//         </div>

//         <!-- Seasons -->
//         <div class="p-4 mx-auto text-white">
//             <div class="mt-6">
//                 <div class="flex mt-2 space-x-2">
//                     @foreach ($series['seasons'] as $season)
//                         <x-secondary-button wire:click="loadSeasonEpisodes({{ $season['season_number'] }})"
//                             class="px-4 py-2 rounded {{ $selectedSeason === $season['season_number'] ? 'bg-gray-900' : 'bg-gray-700' }}">
//                             {{ $season['name'] }}
//                         </x-secondary-button>
//                     @endforeach
//                 </div>
//             </div>

//             <!-- Episode list -->
//             <div class="mt-6">
//                 @if (count($episodes) > 0)
//                     <div class="swiper">
//                         <div class="swiper-wrapper">
//                             @foreach ($episodes as $episode)
//                                 <div class="swiper-slide">
//                                     <x-episode-card :episode="$episode" :series="$series" :selectedSeason="$selectedSeason" />
//                                 </div>
//                             @endforeach
//                         </div>
                        
//                         <div class="swiper-button-prev top-16">
//                         </div>
//                         <div class="swiper-button-next top-16">
//                         </div>
//                     </div>
//                 @else
//                     <p class="mt-4 text-gray-400">No episodes available.</p>
//                 @endif
//             </div>
//         </div>

//         <!-- Trailer -->
//         @if ($trailerUrl)
//             <div class="mx-auto max-w-7xl">
//                 <div class="w-auto h-auto">
//                     <iframe src="{{ $trailerUrl }}?autoplay=1" title="{{ $series['name'] }} video" frameborder="0"
//                         allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
//                         allowfullscreen class="w-full rounded-md h-[100vh]"></iframe>
//                 </div>
//             </div>
//         @endif

//         <div class="px-6 py-10">
//             <livewire:series.popular />
//         </div>
//     </div>
// </div> --}}

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
        $this->loadSeriesDetails($id);
        $this->loadSeasonEpisodes($this->selectedSeason);
    }

    public function loadSeriesCast($id)
    {
        $this->cast = Cache::remember("series_{$id}_cast", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/tv/{$id}/credits")
                ->json();
        });
    }

    public function loadSeriesTrailer($id)
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

    public function loadSeriesData($id)
    {
        $this->series = Cache::remember("series_{$id}", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/tv/{$id}")
                ->json();
        });
    }

    public function loadSeriesDetails($id)
    {
        $this->series = Cache::remember("series_{$id}_details", 3600, function () use ($id) {
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

    public function loadSeriesGenres()
    {
        $genresArray = Cache::remember('series_genres', 3600, function () {
            return Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/genre/tv/list')->json()['genres'] ?? [];
        });

        $genres = collect($genresArray)->mapWithKeys(fn($genre) => [$genre['id'] => $genre['name']]);
        $this->seriesGenres = $genres;
    }
}; ?>

<div class="bg-black">
    <div class="z-30 w-full h-[90vh] mx-auto max-w-7xl">
        <img src="https://image.tmdb.org/t/p/original{{ $series['backdrop_path'] ?? '' }}"
            class="absolute top-0 left-0 object-cover w-full h-full" alt="{{ $series['name'] }}">
        <div class="absolute inset-0 bg-gradient-to-b from-black via-transparent to-black"></div>

        <div class="flex items-end justify-start h-[80vh]">
            <div class="relative p-4 text-white">
                <div class="space-y-2">
                    <h2 class="font-bold text-7xl">{{ $series['name'] }}</h2>

                    <div class="flex items-center gap-3 text-gray-400">
                        <p>{{ $series['adult'] ? '18+' : '13+' }}</p>
                        <p>{{ $series['number_of_seasons'] }} seasons</p>
                        <p>{{ $firstAirDate = \Carbon\Carbon::parse($series['first_air_date'])->year }}</p>
                    </div>

                    <x-primary-button class="absolute px-16">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="text-black size-6">
                                <path fill-rule="evenodd"
                                    d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="font-bold text-black">Watch now</p>
                        </div>
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>

    <div class="relative h-full mx-auto bg-black max-w-7xl">
        <div class="max-w-4xl px-4 text-gray-300">
            <!-- Overview -->
            @if ($series['overview'])
                <p>{{ $series['overview'] }}</p>
            @endif

            <!-- Cast -->
            @if ($this->cast)
                <p class="py-2 text-sm text-gray-400">
                    @foreach (array_slice($this->cast['cast'], 0, 5) as $actor)
                        {{ $actor['name'] }}@if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </p>
            @endif

            <!-- Genre -->
            <p class="py-2 text-sm text-gray-500">
                @foreach ($series['genres'] as $genre)
                    {{ $seriesGenres[$genre['id']] ?? '' }}@if (!$loop->last)
                        ,
                    @endif
                @endforeach
            </p>
        </div>

        <!-- Seasons -->
        <div class="p-4 mx-auto">
            <div class="mt-6">
                <div class="flex mt-2 space-x-2">
                    @foreach ($series['seasons'] as $season)
                        <x-secondary-button wire:click="loadSeasonEpisodes({{ $season['season_number'] }})"
                            class="px-4 py-2 rounded {{ $selectedSeason === $season['season_number'] ? 'bg-gray-900' : 'bg-gray-700' }}">
                            {{ $season['name'] }}
                        </x-secondary-button>
                    @endforeach
                </div>
            </div>

            <!-- Episode list -->
            <div class="mt-6">
                @if (count($episodes) > 0)
                    <div class="overflow-hidden episode-swiper" id="episode-swiper">
                        <div class="swiper-wrapper">
                            @foreach ($episodes as $episode)
                                <div class="text-gray-300 swiper-slide hover:text-white">
                                    <x-episode-card :episode="$episode" :series="$series" :selectedSeason="$selectedSeason" />
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="swiper-button-prev top-72 left-6"></div>
                        <div class="swiper-button-next top-72 right-6"></div>
                    </div>
                    
                @else
                    <p class="mt-4 text-gray-400">No episodes available.</p>
                @endif
            </div>
        </div>

        <!-- Trailer -->
        @if ($trailerUrl)
            <div class="mx-auto max-w-7xl">
                <div class="w-auto h-auto">
                    <iframe src="{{ $trailerUrl }}?autoplay=1" title="{{ $series['name'] }} video" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen class="w-full rounded-md h-[100vh]"></iframe>
                </div>
            </div>
        @endif

        <div class="px-6 py-10">
            <livewire:series.popular />
        </div>
    </div>
</div>
