<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

new class extends Component {
    public $series = [];
    public $genreName = '';

    public function mount($genreId)
    {
        $this->genreName = Cache::remember("genre_name_{$genreId}", 3600, function () use ($genreId) {
            $response = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/genre/tv/list')->json();
            $genre = collect($response['genres'])->firstWhere('id', $genreId);

            if (!$genre) {
                abort(404);
            } else {
                return $genre['name'] ?? '';
            }
        });
        $this->dispatch('livewireFetchedData');

        $this->series = Cache::remember("series_by_genre_{$genreId}", 3600, function () use ($genreId) {
            return collect(
                Http::withToken(config('services.tmdb.token'))
                    ->get('https://api.themoviedb.org/3/discover/tv', [
                        'with_genres' => $genreId,
                    ])
                    ->json()['results'],
            )->shuffle() ?? [];
        });
    }
}; ?>

<div class="swiper">
    <h2 class="my-4 text-2xl font-bold text-white">{{ $this->genreName }}</h2>
    <div class="swiper-wrapper">
        @foreach ($series as $index => $serie)
            <div class="swiper-slide">
                <div wire:key="item-{{ $serie['id'] }}">
                    <x-series-card :series="$serie" :index="$index" :loaded="true" />
                </div>
            </div>
        @endforeach
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
