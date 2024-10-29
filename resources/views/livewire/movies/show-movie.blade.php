<?php

use Livewire\Volt\Component;

new class extends Component {
    public $movie;
    public $image;

    public function mount($id)
    {
        $this->movie = Cache::remember("movies_{$id}", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/movie/{$id}")
                ->json();
        });
        // dd($this->movie);

        $this->image = Cache::remember("movies_{$id}_", 3600, function () use ($id) {
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/movie/{$id}/images")
                ->json();
        });
    }
}; ?>


<div class="bg-black">
    <div class="z-30 w-full h-screen mx-auto max-w-7xl">
        <img src="https://image.tmdb.org/t/p/original{{ $image['backdrops'][0]['file_path'] }}"
            class="absolute top-0 left-0 object-cover w-full h-full" alt="{{ $movie['title'] }}">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black"></div>

        <div class="flex items-end justify-start h-full">
            <div class="relative p-4 text-white">
                <div class="space-y-2 ">
                    <h2 class="font-bold text-7xl">{{ $movie['title'] }}</h2>

                    <div class="flex items-center gap-3 text-gray-400">
                        <p>{{ $movie['adult'] ? '18+' : '13+' }}</p>
                        <p>{{ floor($movie['runtime'] / 60) }}h {{ $movie['runtime'] % 60 }}min</p>
                        <p>{{ $releaseYear = \Carbon\Carbon::parse($movie['release_date'])->year }}</p>
                    </div>

                    <x-primary-button class="px-16">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="text-black size-6">
                                <path fill-rule="evenodd"
                                    d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="font-bold text-black">Watch now</p>
                        </div>
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>

    <div class="relative mx-auto bg-black max-w-7xl">
        <div class="max-w-4xl p-4 text-gray-300">
            <p>{{ $movie['overview'] }}</p>
        </div>
    </div>
</div>
