<?php
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

new class extends Component 
{
    protected $listeners = ['watchlistUpdated'];

    public $watchlist = [];

    public function mount()
    {
        $this->loadWatchlist();
    }

    public function watchlistUpdated($inWatchlist)
    {
        $this->loadWatchlist();
    }

    public function loadWatchlist()
    {
        $this->watchlist = collect(Auth::user()->watchlist)
            ->map(function ($item) {
                return Cache::remember("watchlist_item_{$item->item_id}", 3600, function () use ($item) {
                    return $this->fetchItemDetails($item->item_id);
                });
            })
            ->filter();
    }

    private function fetchItemDetails($itemId)
    {
        $url = "https://api.themoviedb.org/3/movie/{$itemId}";
        $response = Http::withToken(config('services.tmdb.token'))->get($url);

        if (!$response->successful()) {
            $url = "https://api.themoviedb.org/3/tv/{$itemId}";
            $response = Http::withToken(config('services.tmdb.token'))->get($url);
        }

        return $response->successful() ? $response->json() : null;
    }
}; ?>

<div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 gap-4 mt-10 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
        @forelse($this->watchlist as $index => $item)
            <div wire:key="item-{{ $item['id'] }}">
                @if (isset($item['title']))
                    <x-movie-card :movie="$item" :index="$index" :loaded="true" />
                @elseif (isset($item['name']))
                    <x-series-card :series="$item" :index="$index" :loaded="true" />
                @endif
            </div>
        @empty
            <p class="absolute font-bold text-gray-500 text-7xl center">No movies in your watchlist yet.</p>
        @endforelse
    </div>
</div>