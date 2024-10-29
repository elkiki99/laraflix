<?php

use Livewire\Volt\Component;

new class extends Component 
{
    public $popularSeries = [];

    public function mount()
    {
        $this->loadPopularSeries();
    }

    public function loadPopularSeries()
    {
        $this->popularSeries = Cache::remember('popular_series', 3600, function () {
            return Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/tv/popular')->json()['results'];
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper">
    <h2 class="my-4 text-2xl font-bold">Popular series</h2>

    <div class="swiper-wrapper">
        @foreach ($popularSeries as $index => $series)
            <div class="swiper-slide">
                <x-series-card :series="$series" :index="$index" />
            </div>
        @endforeach
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
