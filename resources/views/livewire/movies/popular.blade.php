<?php

use Livewire\Volt\Component;

new class extends Component {
    public $popular = [];

    public function mount()
    {
        $this->loadPopular();
    }

    public function loadPopular()
    {
        $this->popular = Cache::remember('popular_movies', 3600, function () {
            return Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/popular')->json()['results'];
        });
        $this->dispatch('dataWasLoadedByLivewire');
    }
}; ?>

<div class="swiper">
    <h2 class="my-4 text-2xl font-bold">Popular</h2>

    <div class="swiper-wrapper">
        @foreach ($popular as $index => $movie)
            <div class="swiper-slide">
                <x-movie-card :movie="$movie" :index="$index" />
            </div>
        @endforeach
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

    <style>
        .swiper {
            width: full;
            height: 400px;
        }
    </style>
</div>
