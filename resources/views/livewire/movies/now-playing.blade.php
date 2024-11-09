<?php

use Livewire\Volt\Component;

new class extends Component {
    public $nowPlaying = [];

    public function loadNowPlaying()
    {
        $this->nowPlaying = Cache::remember('now_playing_movies', 3600, function () {
            return collect(Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/now_playing')->json()['results'])->shuffle();
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper" x-intersect.once="$wire.loadNowPlaying()">
    <h2 class="my-4 text-2xl font-bold text-white">Now playing</h2>

    <div class="swiper-wrapper">
        @foreach ($nowPlaying as $index => $movie)
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
