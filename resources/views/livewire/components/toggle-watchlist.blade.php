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

<div>
    <button wire:click="toggleWatchlist">
        @if($inWatchlist)
            {{ __('Remove from watchlist') }}
        @else
            {{ __('Add to watchlist') }}
        @endif
    </button>
</div>
