<?php

use Livewire\Volt\Component;

new class extends Component {
    public $topRated = [];

    // public function mount()
    // {
    //     $this->loadTopRated();
    // }

    public function loadTopRated()
    {
        $this->topRated = Cache::remember('top_rated', 3600, function () {
            return collect(Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/tv/top_rated')->json()['results'])->shuffle();
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper" x-intersect.once="$wire.loadTopRated()">
    <h2 class="my-4 text-2xl font-bold text-white">Top rated</h2>

    <div class="swiper-wrapper">
        @foreach ($topRated as $index => $series)
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
