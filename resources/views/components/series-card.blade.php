@props(['series', 'index'])

<div x-data="{ loaded: true }"
    class="relative overflow-hidden transition duration-300 transform bg-gray-800 rounded-sm shadow-md hover:cursor-pointer hover:shadow-xl hover:scale-105">
    <div class="absolute inset-0 flex items-center justify-center" x-show="!loaded">
        <div class="w-8 h-8 border-4 border-gray-200 rounded-full border-t-gray-500 animate-spin"></div>
    </div>

    <div class="relative">
        <a href="{{ route('series.show', $series['id']) }}">
            <img loading="{{ $index < 6 ? 'eager' : 'lazy' }}"
                src="https://image.tmdb.org/t/p/w500{{ $series['poster_path'] }}" alt="{{ $series['name'] }}"
                class="object-cover w-full transition duration-500 ease-in-out opacity-0 h-80" @load="loaded = true"
                :class="loaded ? 'opacity-100' : 'opacity-0'">
        </a>

        <div class="absolute top-2 right-2">
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
                        <livewire:components.toggle-watchlist :itemId="$series['id']" />
                    </x-dropdown-link>

                    <x-dropdown-link href="{{ route('series.show', $series['id']) }}">
                        {{ __('More info') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</div>
