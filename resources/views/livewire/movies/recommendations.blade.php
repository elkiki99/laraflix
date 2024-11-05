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
        $this->loadRecommendations();
    }

    protected function loadRecommendations()
    {
        $this->recommendations = Cache::remember("movies_{$this->id}_recommendations", 3600, function () {
            $response = Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/movie/{$this->id}/similar")
                ->json();
            return isset($response['results']) ? collect($response['results'])->shuffle() : collect([]);
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="">
    @if (isset($recommendations['results']) || count($recommendations) > 0)
        <div class="swiper">
            <h2 class="my-4 text-2xl font-bold text-white">Recommended</h2>

            <div class="swiper-wrapper">
                @if (isset($recommendations['results']))
                    @foreach ($recommendations['results'] as $index => $movie)
                        <div class="swiper-slide">
                            <x-movie-card :movie="$movie" :index="$index" />
                        </div>
                    @endforeach
                @else
                    @foreach ($recommendations as $index => $movie)
                        <div class="swiper-slide">
                            <x-movie-card :movie="$movie" :index="$index" />
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    @endif
</div>
