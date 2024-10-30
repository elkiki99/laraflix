<?php

use Livewire\Volt\Component;

new class extends Component {
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
    }

}; ?>

<div class="swiper">
    <h2 class="my-4 text-2xl font-bold text-white">Popular</h2>

    <div class="swiper-wrapper">
        @foreach ($popularMovies as $index => $movie)
            <div class="swiper-slide">
                <x-movie-card :movie="$movie" :index="$index" />
            </div>
        @endforeach
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
