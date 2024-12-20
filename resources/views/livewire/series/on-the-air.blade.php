<?php

use Livewire\Volt\Component;

new class extends Component 
{
    public $onTheAir = [];

    // public function mount()
    // {
    //     $this->loadOnTheAir();
    // }

    public function loadOnTheAir()
    {
        $this->onTheAir = Cache::remember('on_the_air', 3600, function () {
            return collect(Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/tv/on_the_air')->json()['results'])->shuffle();
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper" x-intersect.once="$wire.loadOnTheAir()">
    <h2 class="my-4 text-xl font-medium text-white">On the air</h2>

    <div class="swiper-wrapper">
        @foreach ($onTheAir as $index => $series)
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
