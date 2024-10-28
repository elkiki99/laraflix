<?php

use Livewire\Volt\Component;

new class extends Component 
{
    public $onTheAir = [];

    public function mount()
    {
        $this->loadOnTheAir();
    }

    public function loadOnTheAir()
    {
        $this->onTheAir = Cache::remember('on_the_air', 3600, function () {
            return Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/tv/on_the_air')->json()['results'];
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper">
    <h2 class="my-4 text-2xl font-bold">On the air</h2>

    <div class="swiper-wrapper">
        @foreach ($onTheAir as $index => $series)
            <div class="swiper-slide">
                <x-series-card :series="$series" :index="$index" />
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
        .swiper-button-next, .swiper-button-prev {
            color: white;
        }
    </style>
</div>