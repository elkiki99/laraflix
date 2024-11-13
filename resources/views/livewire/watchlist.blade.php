<?php
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

new class extends Component {
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
                $cacheKey = "watchlist_item_{$item->item_type}_{$item->item_id}";

                return Cache::remember($cacheKey, 3600, function () use ($item) {
                    return $this->fetchItemDetails($item->item_id, $item->item_type);
                });
            })
            ->filter()
            ->reverse();
    }

    private function fetchItemDetails($itemId, $itemType)
    {
        $url = $itemType === 'movie' ? "https://api.themoviedb.org/3/movie/{$itemId}" : "https://api.themoviedb.org/3/tv/{$itemId}";

        $response = Http::withToken(config('services.tmdb.token'))->get($url);

        return $response->successful() ? $response->json() : null;
    }
}; ?>

<div class="mx-auto max-w-7xl">
    <div class="grid grid-cols-3 gap-2 mt-10 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
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
