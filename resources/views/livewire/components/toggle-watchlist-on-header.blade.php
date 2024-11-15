<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Watchlist;

new class extends Component {
    public $itemId;
    public $itemType;
    public $inWatchlist;

    public function mount($itemId, $itemType)
    {
        $this->itemId = $itemId;
        $this->itemType = $itemType;
        $this->inWatchlist = Auth::user()->watchlist->pluck('item_id')->contains($itemId);
    }

    public function addToWatchlist()
    {
        Watchlist::create([
            'user_id' => Auth::id(),
            'item_type' => $this->itemType,
            'item_id' => $this->itemId,
        ]);
        $this->inWatchlist = true;
    }

    public function removeFromWatchlist()
    {
        Watchlist::where('user_id', Auth::id())
            ->where('item_id', $this->itemId)
            ->where('item_type', $this->itemType)
            ->delete();
        $this->inWatchlist = false;
    }

    public function toggleWatchlist()
    {
        if ($this->inWatchlist) {
            $this->removeFromWatchlist();
        } else {
            $this->addToWatchlist();
        }

        $this->dispatch('updateWatchlistInRealTime');
    }
}; ?>

<div x-data="{ inWatchlist: @js($inWatchlist) }" class="mb-4">
    <button @click="inWatchlist = !inWatchlist; $wire.toggleWatchlist()">
        <div class="flex flex-col items-center justify-center ">
            <!-- Plus Icon -->
            <svg x-show="!inWatchlist" x-transition:enter="transition transform duration-300 ease-in-out"
                x-transition:enter-start="opacity-0 rotate-180 scale-50"
                x-transition:enter-end="opacity-100 rotate-0 scale-100"
                x-transition:leave="transition transform duration-300 ease-in-out"
                x-transition:leave-start="opacity-100 rotate-0 scale-100"
                x-transition:leave-end="opacity-0 rotate-180 scale-50" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="absolute w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>

            <!-- Check Icon -->
            <svg x-show="inWatchlist" x-transition:enter="transition transform duration-300 ease-in-out"
                x-transition:enter-start="opacity-0 -rotate-180 scale-50"
                x-transition:enter-end="opacity-100 rotate-0 scale-100"
                x-transition:leave="transition transform duration-300 ease-in-out"
                x-transition:leave-start="opacity-100 rotate-0 scale-100"
                x-transition:leave-end="opacity-0 -rotate-180 scale-50" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="absolute w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        </div>

        <p class="mt-4 text-xs">Watchlist</p>
    </button>
</div>
