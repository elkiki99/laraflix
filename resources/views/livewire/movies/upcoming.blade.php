<?php

use Livewire\Volt\Component;

new class extends Component 
{
    public $upcoming = [];

    public function mount()
    {
        $this->loadUpcoming();
    }

    public function loadUpcoming()
    {
        $this->upcoming = Cache::remember('upcoming_movies', 3600, function () {
            return Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/upcoming')->json()['results'];
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper">
    <h2 class="my-4 text-2xl font-bold">Upcoming</h2>

    <div class="swiper-wrapper">
        @foreach ($upcoming as $index => $movie)
            <div class="swiper-slide">
                <x-movie-card :movie="$movie" :index="$index" />
            </div>
        @endforeach
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>