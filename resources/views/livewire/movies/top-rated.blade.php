<?php

use Livewire\Volt\Component;

new class extends Component {
    public $topRated = [];

    public function loadTopRated()
    {
        $this->topRated = Cache::remember('top_rated_movies', 3600, function () {
            return collect(Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/top_rated')->json()['results'])->shuffle();
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper" x-intersect.once="$wire.loadTopRated()">
    <h2 class="my-4 text-xl font-medium text-white">Top Rated</h2>

    <div class="swiper-wrapper">
        @foreach ($topRated as $index => $movie)
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
