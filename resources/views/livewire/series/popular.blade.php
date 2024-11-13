<?php

use Livewire\Volt\Component;

new class extends Component 
{
    public $popularSeries = [];

    public function loadPopularSeries()
    {
        $this->popularSeries = Cache::remember('popular_series', 3600, function () {
            return collect(Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/tv/popular')->json()['results'])->shuffle();
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper" x-intersect.once="$wire.loadPopularSeries()">
    <h2 class="my-4 text-xl font-medium text-white">Popular series</h2>

    <div class="swiper-wrapper">
        @foreach ($popularSeries as $index => $series)
            <div class="swiper-slide">
                <div wire:key="item-{{ $series['id'] }}">
                    <x-series-card :series="$series" :index="$index" :loaded="true" />
                </div>
            </div>
        @endforeach
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
