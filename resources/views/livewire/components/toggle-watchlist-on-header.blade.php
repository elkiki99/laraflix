<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Watchlist;

new class extends Component {
    public $itemId;
    public $inWatchlist;

    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->inWatchlist = Auth::user()->watchlist->pluck('item_id')->contains($itemId);
    }

    public function addToWatchlist()
    {
        Watchlist::create([
            'user_id' => Auth::id(),
            'item_id' => $this->itemId,
        ]);
        $this->inWatchlist = true;
    }

    public function removeFromWatchlist()
    {
        Watchlist::where('user_id', Auth::id())
            ->where('item_id', $this->itemId)
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

<div class="mb-4">
    <button wire:click="toggleWatchlist">
        <div class="flex flex-col items-center justify-center gap-1">
            @if ($inWatchlist)
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class=" size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            @endif
            
            <p class="text-xs">Watchlist</p>
        </div>
    </button>
</div>
