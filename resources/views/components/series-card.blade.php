@props(['series', 'index', 'loaded' => false])

<div x-data="{ loaded: @js($loaded) }" wire:remove wire:key="item-{{ $series['id'] }}"
    wire:target='toggle-watchlist,{{ $series['id'] }}'
    class="max-w-[250px] relative overflow-hidden transition duration-300 bg-gray-800 border border-transparent rounded-sm shadow-md hover:cursor-pointer hover:border-gray-400 hover:shadow-lg hover:shadow-gray-200/50">

    <div class="relative aspect-w-2 aspect-h-3">
        <div class="absolute inset-0 flex items-center justify-center" x-show="!loaded">
            <div class="w-8 h-8 border-4 border-gray-200 rounded-full border-t-gray-500 animate-spin"></div>
        </div>

        <a href="{{ route('series.show', $series['id']) }}">
            <img loading="{{ $index < 7 ? 'eager' : 'lazy' }}"
                src="https://image.tmdb.org/t/p/w500{{ $series['poster_path'] }}" alt="{{ $series['name'] }}"
                class="object-cover w-full h-auto transition duration-500 ease-in-out opacity-0" @load="loaded = true"
                :class="loaded ? 'opacity-100' : 'opacity-0'">
        </a>

        <div class="hidden sm:absolute top-2 right-2">
            <x-dropdown align="right" width="48" class="bg-black bg-opacity-50">
                <x-slot name="trigger">
                    <button>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="text-white size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link>
                        <livewire:components.toggle-watchlist :itemId="$series['id']" :itemType="'series'" :key="$series['id']" />
                    </x-dropdown-link>

                    <x-dropdown-link href="{{ route('series.show', $series['id']) }}">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg>

                            <p class="text-xs">More info</p>
                        </div>
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
        </div>

        <livewire:components.toggle-watchlist-on-mobile :itemId="$series['id']" :itemType="'series'" :key="$series['id']" />
    </div>
</div>
