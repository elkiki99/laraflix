<?php

use Livewire\Volt\Component;

new class extends Component 
{
    public $serie;
    public $image;

    public function mount($id)
    {
        $this->serie = Cache::remember("series_{$id}", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/tv/{$id}")
                ->json();
        });

        $this->image = Cache::remember("tv_{$id}_images", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/tv/{$id}/images")
                ->json();
        });
    }
}; ?>

<div class="z-30 w-full">
    <img src="https://image.tmdb.org/t/p/original{{ $image['backdrops'][0]['file_path'] }}"
        class="absolute top-0 left-0 w-full h-auto" alt="{{ $serie['name'] }}">
</div>
