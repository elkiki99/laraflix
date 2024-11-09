<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

new class extends Component {
    public $id;
    public $recommendations = [];

    public function mount($id)
    {
        $this->id = $id;
    }

    public function loadRecommendations()
    {
        $this->recommendations = Cache::remember("series{$this->id}_recommendations", 3600, function () {
            $response = Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/tv/{$this->id}/similar")
                ->json();
            return isset($response['results']) ? collect($response['results'])->shuffle() : collect([]);
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="" x-intersect.once="$wire.loadRecommendations">
    @if (isset($recommendations['results']) || count($recommendations) > 0)
        <div class="swiper">
            <h2 class="my-4 text-2xl font-bold text-white">Recommended</h2>

            <div class="swiper-wrapper">
                @if (isset($recommendations['results']))
                    @foreach ($recommendations['results'] as $index => $series)
                        <div class="swiper-slide">
                            <div wire:key="item-{{ $series['id'] }}">
                                <x-series-card :series="$series" :index="$index" />
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach ($recommendations as $index => $series)
                        <div class="swiper-slide">
                            <div wire:key="item-{{ $series['id'] }}">
                                <x-series-card :series="$series" :index="$index" :loaded="true" />
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    @endif
</div>
