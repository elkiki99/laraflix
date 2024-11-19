<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

new class extends Component {
    public $trendingMovies = [];

    public function mount()
    {
        $this->loadTrendingMoviesAndSeries();
    }

    public function loadTrendingMoviesAndSeries()
    {
        $this->trendingMovies = Cache::remember('home_header', 360, function () {
            $results = collect(array_merge(Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/trending/movie/day')->json()['results'] ?? [], Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/trending/tv/day')->json()['results'] ?? []));

            if ($results->isEmpty()) {
                return [];
            }

            return $results
                ->shuffle()
                ->take(8)
                ->map(function ($item) {
                    return [
                        'id' => $item['id'],
                        'imgSrc' => 'https://image.tmdb.org/t/p/original/' . $item['backdrop_path'],
                        'imgAlt' => $item['title'] ?? $item['name'],
                        'title' => $item['title'] ?? $item['name'],
                        'description' => Str::limit($item['overview'], 400, '...') ?? $item['overview'],
                        'type' => isset($item['title']) ? 'movie' : 'tv',
                    ];
                })
                ->toArray();
        });

        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="min-h-[70vh] md:min-h-screen">
    <div x-data="{
        slides: {{ json_encode($trendingMovies) }},
        currentSlideIndex: 0,
        next() {
            this.currentSlideIndex = (this.currentSlideIndex + 1) % this.slides.length;
        },
        previous() {
            this.currentSlideIndex = (this.currentSlideIndex - 1 + this.slides.length) % this.slides.length;
        }
    }" x-init="setInterval(() => previous(), 8000)" class="absolute inset-0">
    
        <!-- Background Gradient -->
        <div class="relative md:min-h-screen min-h-[70vh] inset-0 z-20 bg-gradient-to-b from-black via-transparent to-gray-950"></div>

        <!-- Slides -->
        <div class="w-full md:min-h-screen min-h-[70vh]">
            <template x-for="(slide, index) in slides">
                <div x-show="currentSlideIndex == index" class="absolute inset-0" x-transition.scale.origin.right>

                    <!-- Title and description -->
                    <div class="flex items-end justify-start md:min-h-[90vh] min-h-[80vh] mx-auto max-w-7xl">
                        <div class="z-30 p-4 text-white">
                            <div class="space-y-2 max-w-[80%]">
                                <h2 class="text-5xl font-medium md:font-bold md:text-7xl" x-text="slide.title"
                                    x-bind:aria-describedby="'slide' + (index + 1) + 'Title'"></h2>

                                <div class="flex items-center gap-3 text-xs text-gray-300 md:text-base">
                                    <p x-text="slide.description" x-bind:id="'slide' + (index + 1) + 'Description'"></p>
                                </div>

                                <div class="pt-2">
                                    <a
                                        wire:navigate
                                        :href="slide.type === 'movie' ? `{{ route('movies.show', '') }}/${slide.id}` :
                                            `{{ route('series.show', '') }}/${slide.id}`">
                                        <x-primary-button class="px-16">
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="text-black size-4 md:size-6">
                                                    <path fill-rule="evenodd"
                                                        d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <p class="text-xs font-bold text-black md:text-md">Go to <span
                                                        x-text="slide.type === 'movie' ? 'movie' : 'series'"></span></p>
                                            </div>
                                        </x-primary-button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image -->
                    <img loading="eager" class="absolute top-0 left-0 object-cover w-full md:h-screen h-[70vh]" alt="slide.imgAlt"
                        x-bind:src="slide.imgSrc" x-bind:alt="slide.imgAlt">
                </div>
            </template>
        </div>

        <!-- Indicators -->
        <div class="hidden absolute rounded-xl bottom-3  md:bottom-5 left-1/2 z-20 md:flex -translate-x-1/2 gap-4 md:gap-3 px-1.5 py-1 md:px-2"
            role="group" aria-label="slides">
            <template x-for="(slide, index) in slides">
                <button class="transition rounded-full cursor-pointer size-2" x-on:click="currentSlideIndex = index"
                    x-bind:class="[currentSlideIndex === index ? 'bg-slate-300' : 'bg-slate-300/50']"
                    x-bind:aria-label="'slide ' + (index + 1)"></button>
            </template>
        </div>
    </div>
</div>
