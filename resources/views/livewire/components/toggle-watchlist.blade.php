<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Watchlist;

new class extends Component {
    public $itemId;
    public $itemType;
    public $inWatchlist = false;

    public function mount($itemId, $itemType)
    {
        $this->itemId = $itemId;
        $this->itemType = $itemType;
        $this->inWatchlist = Auth::user()->watchlist->where('item_type', $itemType)->pluck('item_id')->contains($itemId);
    }

    public function getListeners()
    {
        return [
            'watchlistUpdated' => 'syncWatchlist',
        ];
    }

    public function toggleWatchlist()
    {
        if ($this->inWatchlist) {
            $this->removeFromWatchlist();
        } else {
            $this->addToWatchlist();
        }

        $this->inWatchlist = !$this->inWatchlist;

        $this->dispatch('watchlistUpdated', $this->itemId, $this->itemType, $this->inWatchlist);
    }

    public function syncWatchlist($itemId, $itemType, $inWatchlist)
    {
        if ($this->itemId === $itemId && $this->itemType === $itemType) {
            $this->inWatchlist = $inWatchlist;
        }
    }

    private function addToWatchlist()
    {
        Watchlist::create([
            'user_id' => Auth::id(),
            'item_id' => $this->itemId,
            'item_type' => $this->itemType,
        ]);
    }

    private function removeFromWatchlist()
    {
        Watchlist::where('user_id', Auth::id())
            ->where('item_id', $this->itemId)
            ->where('item_type', $this->itemType)
            ->delete();
    }
}; ?>

<div>
    <div class="hidden sm:block">
        <button class="block w-full text-left" wire:click="toggleWatchlist">
            @if ($inWatchlist)
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4 sm:size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m3 3 1.664 1.664M21 21l-1.5-1.5m-5.485-1.242L12 17.25 4.5 21V8.742m.164-4.078a2.15 2.15 0 0 1 1.743-1.342 48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185V19.5M4.664 4.664 19.5 19.5" />
                    </svg>

                    <p class="text-xs sm:text-sm">Remove from watchlist</p>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4 sm:size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
                    </svg>

                    <p class="text-xs sm:text-sm">Add to watchlist</p>
                </div>
            @endif
        </button>
    </div>

    <div class="absolute z-30 sm:hidden top-2 right-2">
        <button class="block w-full text-left" wire:click="toggleWatchlist">
            @if ($inWatchlist)
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="text-white size-4">
                        <path fill-rule="evenodd"
                            d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="text-white size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
                    </svg>
                </div>
            @endif
        </button>
    </div>
</div>

