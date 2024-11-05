<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

new class extends Component {
    public $movies = [];
    public $genreName = '';

    public function mount($genreId)
    {
        $this->genreName = Cache::remember("genre_name_{$genreId}", 3600, function () use ($genreId) {
            $response = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/genre/movie/list')->json();
            $genre = collect($response['genres'])->firstWhere('id', $genreId);

            if($genre) {
                return $genre['name'] ?? '';
            } else {
                abort(404);
            }
        });
        $this->dispatch('livewireFetchedData');

        $this->movies = Cache::remember("movies_by_genre_{$genreId}", 3600, function () use ($genreId) {
            return collect(Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/discover/movie', [
                    'with_genres' => $genreId,
                ])
                ->json()['results'])->shuffle() ?? [];
        });
    }
}; ?>

<div class="swiper">
    <h2 class="my-4 text-2xl font-bold text-white">{{ $this->genreName }}</h2>
    <div class="swiper-wrapper">
        @foreach ($movies as $index => $movie)
            <div class="swiper-slide">
                <x-movie-card :movie="$movie" :index="$index" />
            </div>
        @endforeach
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>