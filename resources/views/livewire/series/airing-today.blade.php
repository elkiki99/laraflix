<?php

use Livewire\Volt\Component;

new class extends Component 
{
    public $airingToday = [];

    public function mount()
    {
        $this->loadAiringToday();
    }

    public function loadAiringToday()
    {
        $this->airingToday = Cache::remember('airing_today', 3600, function () {
            return Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/tv/airing_today')->json()['results'];
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="swiper">
    <h2 class="my-4 text-2xl font-bold">Airing today</h2>

    <div class="swiper-wrapper">
        @foreach ($airingToday as $index => $series)
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
