<?php

use Livewire\Volt\Component;

new class extends Component {
    public $airingToday = [];

    public function loadAiringToday()
    {
        $this->airingToday = Cache::remember('airing_today', 3600, function () {
            return collect(Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/tv/airing_today')->json()['results'])->shuffle();
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper" x-intersect.once="$wire.loadAiringToday()">
    <h2 class="my-4 text-2xl font-bold text-white">Airing today</h2>

    <div class="swiper-wrapper">
        @foreach ($airingToday as $index => $series)
            <div class="swiper-slide">
                <div wire:key="item-{{ $series['id'] }}">
                    <x-series-card :series="$series" :index="$index" />
                </div>
            </div>
        @endforeach
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
