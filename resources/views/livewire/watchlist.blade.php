<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

new class extends Component 
{
    public $watchlist = [];

    protected $listeners = ['updateWatchlistInRealTime' => 'loadWatchlist'];

    public function mount()
    {
        $this->loadWatchlist();
    }

    // public function updateWatchlistInRealTime()
    // {
    //     $this->loadWatchlist();
    // }

    public function loadWatchlist()
    {
        $this->watchlist = collect(Auth::user()->watchlist)
            ->map(function ($item) {
                $url = "https://api.themoviedb.org/3/movie/{$item->item_id}";
                $response = Http::withToken(config('services.tmdb.token'))->get($url);

                if (!$response->successful()) {
                    $url = "https://api.themoviedb.org/3/tv/{$item->item_id}";
                    $response = Http::withToken(config('services.tmdb.token'))->get($url);
                }
                return $response->successful() ? $response->json() : null;
            })
            ->filter()
            ->shuffle()
            ->toArray();
    }
}; ?>

<div class="grid grid-cols-2 gap-4 mt-10 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
    @forelse($this->watchlist as $index => $item)
        @if (isset($item['title']))
            <x-movie-card :movie="$item" :index="$index" />
        @elseif (isset($item['name']))
            <x-series-card :series="$item" :index="$index" />
        @endif
    @empty
        <p class="absolute font-bold text-gray-500 text-7xl center text-">No movies in your watchlist yet.</p>
    @endforelse
</div>
