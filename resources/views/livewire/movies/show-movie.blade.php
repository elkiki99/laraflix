<?php

use Livewire\Volt\Component;

new class extends Component {
    public $movie;
    public $image;
    public $trailerUrl;
    public $cast = [];
    public $movieGenres = [];
    public $recommendations = [];
    
    public function mount($id)
    {
        $this->loadMovieGenres();
        $this->loadMovieData($id);
        $this->loadMovieImages($id);
        $this->loadMovieCast($id);
        $this->loadMovieTrailer($id);
    }

    protected function loadMovieData($id)
    {
        $this->movie = Cache::remember("movies_{$id}", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/movie/{$id}")
                ->json();
        });
    }

    protected function loadMovieImages($id)
    {
        $this->image = Cache::remember("movies_{$id}_images", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/movie/{$id}/images")
                ->json();
        });
    }

    protected function loadMovieCast($id)
    {
        $this->cast = Cache::remember("movie_{$id}_cast", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/movie/{$id}/credits")
                ->json();
        });
    }

    protected function loadMovieTrailer($id)
    {
        $trailers = Cache::remember("movies_{$id}_trailers", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/movie/{$id}/videos")
                ->json();
        });

        if (!empty($trailers['results']) && count($trailers['results']) > 0) {
            $randomTrailer = collect($trailers['results'])->random();
            $this->trailerUrl = "https://www.youtube.com/embed/{$randomTrailer['key']}";
        } else {
            $this->trailerUrl = null;
        }
    }

    protected function loadMovieGenres()
    {
        $genresArray = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/genre/movie/list')->json()['genres'];

        $genres = collect($genresArray)->mapWithKeys(fn($genre) => [$genre['id'] => $genre['name']]);
        $this->movieGenres = $genres;
    }
}; ?>

<div class="bg-black">
    <div class="z-30 w-full h-[90vh] mx-auto max-w-7xl">
        <img src="https://image.tmdb.org/t/p/original{{ $image['backdrops'][0]['file_path'] ?? ''}}"
            class="absolute top-0 left-0 object-cover w-full h-full" alt="{{ $movie['title'] }}">
        <div class="absolute inset-0 bg-gradient-to-b from-black via-transparent to-black"></div>

        <div class="flex items-end justify-start h-[80vh]">
            <div class="z-10 p-4 text-white">
                <div class="space-y-2">
                    <h2 class="font-bold text-7xl">{{ $movie['title'] }}</h2>

                    <div class="flex items-center gap-3 text-gray-400">
                        <p>{{ $movie['adult'] ? '18+' : '13+' }}</p>
                        <p>{{ floor($movie['runtime'] / 60) }}h {{ $movie['runtime'] % 60 }}min</p>
                        <p>{{ $releaseYear = \Carbon\Carbon::parse($movie['release_date'])->year }}</p>
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

    <div class="relative h-full pb-10 mx-auto bg-black max-w-7xl">
        <div class="max-w-4xl px-4 pb-8 text-gray-300">
            <!-- Overview -->
            @if($movie['overview'])
                <p>{{ $movie['overview'] }}</p>
            @endif
            
            <!-- Cast -->
            @if($this->cast)
                <p class="py-2 text-sm text-gray-400">
                    @foreach (array_slice($cast['cast'], 0, 5) as $actor)
                        {{ $actor['name'] }}@if (!$loop->last),@endif
                    @endforeach
                </p>
            @endif
            
            <!-- Genres -->
            <p class="text-sm text-gray-500">
                @foreach ($movie['genres'] as $genre)
                    {{ $movieGenres[$genre['id']] ?? 'Unknown' }}@if (!$loop->last),@endif
                @endforeach
            </p>
        </div>
        
        @if ($trailerUrl)
            <div class="mx-auto max-w-7xl">
                <div class="w-auto h-auto">
                    <iframe src="{{ $trailerUrl }}?autoplay=1" title="{{ $movie['title'] }} video" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen class="w-full rounded-md h-[100vh]"></iframe>
                </div>
            </div>
        @endif

        <div class="px-6 py-10">
            <livewire:movies.recommendations :id="$movie['id']" />
        </div>
    </div>
</div>
