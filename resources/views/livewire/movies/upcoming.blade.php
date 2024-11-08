<?php

use Livewire\Volt\Component;

new class extends Component {
    public $upcoming = [];

    public function loadUpcoming()
    {
        $this->upcoming = Cache::remember('upcoming_movies', 3600, function () {
            return collect(Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/upcoming')->json()['results'])->shuffle();
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper" x-intersect.once="$wire.loadUpcoming()">
    <h2 class="my-4 text-2xl font-bold text-white">Upcoming</h2>

    <div class="swiper-wrapper">
        @foreach ($upcoming as $index => $movie)
            <div class="swiper-slide">
                <div wire:key="item-{{ $movie['id'] }}">
                    <x-movie-card :movie="$movie" :index="$index" />
                </div>
            </div>
        @endforeach
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
