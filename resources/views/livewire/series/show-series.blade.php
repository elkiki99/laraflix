<?php

use Livewire\Volt\Component;

new class extends Component 
{
    public $serie;
    public $series;

    public function mount($id)
    {
        $this->serie = Cache::remember("series_{$id}", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/tv/{$id}")
                ->json();
        });
    }
}; ?>

<div>
   {{ $serie['name']}}
</div>