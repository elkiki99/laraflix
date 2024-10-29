<?php

use Livewire\Volt\Component;

new class extends Component {
    // public $movieGenres = [];
    public $popularMovies = [];

    public function mount()
    {
        $this->loadPopularMovies();
    }

    public function loadPopularMovies()
    {
        $this->popularMovies = Cache::remember('popular_movies', 3600, function () {
            return collect(Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/popular')->json()['results'])
                ->map(function ($movie) {
                    $movie['slug'] = \Illuminate\Support\Str::slug($movie['title']);
                    return $movie;
                })
                ->all();
        });
        $this->dispatch('livewireFetchedData');
        // $this->loadMovieGenres();
    }

    // public function loadMovieGenres()
    // {
    //     $genresArray = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/genre/movie/list')->json()['genres'];
    //     $genres = collect($genresArray)->mapWithKeys(fn($genre) => [$genre['id'] => $genre['name']]);
    //     $this->movieGenres = $genres;
    // }
}; ?>

<div class="swiper">
    <h2 class="my-4 text-2xl font-bold">Popular</h2>

    <div class="swiper-wrapper">
        @foreach ($popularMovies as $index => $movie)
            <div class="swiper-slide">
                <x-movie-card :movie="$movie" :index="$index" />
                {{-- <p>
                    @foreach ($movie['genre_ids'] as $genre)
                        {{ $movieGenres[$genre] ?? 'Unknown' }}@if (!$loop->last), @endif
                    @endforeach
                </p> --}}
            </div>
        @endforeach
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
