<?php

use Livewire\Volt\Component;

new class extends Component {
    public $trendingMovies = [];

    public function mount()
    {
        $this->loadTrendingMovies();
    }

    public function loadTrendingMovies()
    {
        $this->trendingMovies = Cache::remember('trending_movies', 360, function () {
            return collect(Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/trending/movie/day')->json()['results'])
                ->map(function ($movie) {
                    return [
                        'id' => $movie['id'],
                        'imgSrc' => 'https://image.tmdb.org/t/p/w500/' . $movie['backdrop_path'],
                        'imgAlt' => $movie['title'],
                        'title' => $movie['title'],
                        'description' => $movie['overview'],
                    ];
                })
                ->random(5)
                ->toArray();
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="min-h-screen">
    <div x-data="{
        slides: {{ json_encode($trendingMovies) }},
        currentSlideIndex: 0,
        next() {
            this.currentSlideIndex = (this.currentSlideIndex + 1) % this.slides.length;
        },
        previous() {
            this.currentSlideIndex = (this.currentSlideIndex - 1 + this.slides.length) % this.slides.length;
        }
    }" class="absolute inset-0 bg-gradient-to-b from-black via-transparent to-gray-900">
        <div class="absolute inset-0 z-20 bg-gradient-to-b from-black via-transparent to-gray-900"></div>

        <!-- Previous button -->
        <button type="button"
            class="absolute z-20 flex items-center justify-center p-2 transition -translate-y-1/2 rounded-full left-5 top-1/2 bg-white/40 text-slate-700 hover:bg-white/60 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 active:outline-offset-0 dark:bg-slate-900/40 dark:text-slate-300 dark:hover:bg-slate-900/60 dark:focus-visible:outline-blue-600"
            aria-label="previous slide" x-on:click="previous()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none"
                stroke-width="3" class="size-5 md:size-6 pr-0.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </button>

        <!-- Next button -->
        <button type="button"
            class="absolute z-20 flex items-center justify-center p-2 transition -translate-y-1/2 rounded-full right-5 top-1/2 bg-white/40 text-slate-700 hover:bg-white/60 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 active:outline-offset-0 dark:bg-slate-900/40 dark:text-slate-300 dark:hover:bg-slate-900/60 dark:focus-visible:outline-blue-600"
            aria-label="next slide" x-on:click="next()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none"
                stroke-width="3" class="size-5 md:size-6 pl-0.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </button>

        <!-- Slides -->
        <div class="relative w-full min-h-screen">
            <template x-for="(slide, index) in slides">
                <div x-cloak x-show="currentSlideIndex == index + 1" class="absolute inset-0"
                    x-transition.opacity.duration.1000ms>

                    <!-- Title and description -->
                    <div
                        class="absolute inset-0 z-10 flex flex-col items-center justify-end gap-2 px-16 py-12 text-center lg:px-32 lg:py-14 bg-gradient-to-t from-slate-900/85 to-transparent">
                        <h3 class="w-full lg:w-[80%] text-balance text-2xl lg:text-3xl font-bold text-white"
                            x-text="slide.title" x-bind:aria-describedby="'slide' + (index + 1) + 'Description'"></h3>
                        <p class="w-full text-sm lg:w-1/2 text-pretty text-slate-300" x-text="slide.description"
                            x-bind:id="'slide' + (index + 1) + 'Description'"></p>
                    </div>

                    <!-- Image -->
                    <img class="absolute top-0 left-0 object-cover w-full h-full" alt=""
                        x-bind:src="slide.imgSrc" x-bind:alt="slide.imgAlt">
                </div>
            </template>
        </div>

        <!-- Indicators -->
        <div class="absolute rounded-xl bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-4 md:gap-3 px-1.5 py-1 md:px-2"
            role="group" aria-label="slides">
            <template x-for="(slide, index) in slides">
                <button class="transition rounded-full cursor-pointer size-2" x-on:click="currentSlideIndex = index + 1"
                    x-bind:class="[currentSlideIndex === index + 1 ? 'bg-slate-300' : 'bg-slate-300/50']"
                    x-bind:aria-label="'slide ' + (index + 1)"></button>
            </template>
        </div>
    </div>
</div>
