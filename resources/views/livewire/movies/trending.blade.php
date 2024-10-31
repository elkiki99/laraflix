<?php

use Livewire\Volt\Component;

new class extends Component {
    public $trendingMovies = [];

    public function mount()
    {
        $this->loadTrendingMovies();
    }

    public function loadTrendingMovies()
    {
        $this->trendingMovies = Cache::remember('trending_movies', 3600, function () {
            return collect(Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/trending/movie/day')->json()['results']);
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper">
    <h2 class="my-4 text-2xl font-bold text-white">Trending today</h2>
    <div class="swiper-wrapper">
        @foreach ($trendingMovies as $index => $movie)
            <div class="swiper-slide">
                <x-movie-card :movie="$movie" :index="$index" />
            </div>
        @endforeach
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

