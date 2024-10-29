<?php

use Livewire\Volt\Component;

new class extends Component 
{
    public $movie;
    public $movies;

    public function mount($id)
    {
        $this->movie = Cache::remember("movies_{$id}", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/movie/{$id}")
                ->json();
        });
    }
}; ?>

<div>
   {{ $movie['title']}}
</div>