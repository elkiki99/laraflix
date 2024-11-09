<?php

use Livewire\Volt\Component;

new class extends Component 
{
    public $popularMovies = [];

    public function loadPopularMovies()
    {
        $this->popularMovies = Cache::remember('popular_movies', 3600, function () {
            return collect(Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/popular')->json()['results'])->shuffle();
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper" x-intersect.once="$wire.loadPopularMovies()">
    <h2 class="my-4 text-2xl font-bold text-white">Popular</h2>
    <div class="swiper-wrapper">
        @foreach ($popularMovies as $index => $movie)
            <div class="swiper-slide">
                <div wire:key="item-{{ $movie['id'] }}">
                    <x-movie-card :movie="$movie" :index="$index" :loaded="true" />
                </div>
            </div>
        @endforeach
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
