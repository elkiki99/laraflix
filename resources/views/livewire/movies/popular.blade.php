<?php

use Livewire\Volt\Component;

new class extends Component {
    public $popular = [];

    public function loadPopular()
    {
        $this->popular = Cache::remember('popular_movies', 3600, function () {
            return Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/popular')->json()['results'];
        });
    }
}; ?>

<div class="min-h-screen" x-data="{ shown: false }" x-intersect.once="shown = true; $wire.loadPopular()">
    <h2 class="my-4 text-2xl font-bold">Popular</h2>
    <div class="grid grid-cols-2 pb-2 gap-x-2 gap-y-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6" x-show="shown"
        x-transition>
        @foreach ($popular as $index => $movie)
            <x-movie-card :movie="$movie" :index="$index" />
        @endforeach
    </div>
</div>
