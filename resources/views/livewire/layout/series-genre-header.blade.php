<?php

use Livewire\Volt\Component;

new class extends Component {
    public $series = [];

    public function mount($genreId)
    {
        $this->loadSeries($genreId);
    }
    
    public function loadSeries($genreId)
    {
        $this->series = Cache::remember("header_by_genre_{$genreId}", 3600, function () use ($genreId) {
            return collect(Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/discover/tv', [
                    'with_genres' => $genreId,
                ])
                ->json()['results'])->map(function($series) {
                    return [
                        'id' => $series['id'],
                        'imgSrc' => 'https://image.tmdb.org/t/p/original/' . $series['backdrop_path'],
                        'imgAlt' => $series['name'],
                        'title' => $series['name'],
                        'description' => $series['overview'],
                    ];
                })
                ->shuffle()
                ->random(8)
                ->toArray();
        });
        $this->dispatch('livewireFetchedData');
    }
}; ?>

<div class="min-h-screen">
    <div x-data="{
            slides: {{ json_encode($series) }},
            currentSlideIndex: 0,
            next() {
                this.currentSlideIndex = (this.currentSlideIndex + 1) % this.slides.length;
            },
            previous() {
                this.currentSlideIndex = (this.currentSlideIndex - 1 + this.slides.length) % this.slides.length;
            }
        }" 
        x-init="setInterval(() => previous(), 8000)" 
        class="absolute inset-0"
    >
        <div class="absolute inset-0 z-20 bg-gradient-to-b from-black via-transparent to-gray-950"></div>

        <!-- Slides -->
        <div class="relative w-full min-h-screen">
            <template x-for="(slide, index) in slides">
                <div x-show="currentSlideIndex == index" class="absolute inset-0" x-transition.scale.origin.right>

                    <!-- Title and description -->
                    <div class="flex items-end justify-start h-[90vh] mx-auto max-w-7xl">
                        <div class="z-20 p-4 text-white">
                            <div class="space-y-2 max-w-[80%]">
                                <h2 class="font-bold text-7xl" x-text="slide.title"
                                    x-bind:aria-describedby="'slide' + (index + 1) + 'Description'"></h2>

                                <div class="flex items-center gap-3 text-gray-300">
                                    <p x-text="slide.description" x-bind:id="'slide' + (index + 1) + 'Description'"></p>
                                </div>

                                <div class="">
                                    <a :href="`{{ route('series.show', '') }}/${slide.id}`">
                                        <x-primary-button class="absolute px-16 hover:cursor-pointer">
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="text-black size-6">
                                                    <path fill-rule="evenodd"
                                                        d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <p class="font-bold text-black">Watch now</p>
                                            </div>
                                        </x-primary-button>
                                    </a>
                                </div>
                            </div>
                        </div>
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
                <button class="transition rounded-full cursor-pointer size-2" x-on:click="currentSlideIndex = index"
                    x-bind:class="[currentSlideIndex === index ? 'bg-slate-300' : 'bg-slate-300/50']"
                    x-bind:aria-label="'slide ' + (index + 1)"></button>
            </template>
        </div>
    </div>
</div>

