@props(['episode', 'series', 'selectedSeason'])

<div x-data="{ loaded: true }"
    class="relative overflow-hidden text-gray-300 bg-black rounded-sm shadow-md hover:text-white hover:cursor-pointer hover:shadow-xl group">
    <div class="absolute inset-0 flex items-center justify-center" x-show="!loaded">
        <div class="w-8 h-8 border-4 border-gray-200 rounded-full border-t-gray-500 animate-spin">
        </div>
    </div>

    <div class="m-0.5">
        <!-- Image -->
        <a href="#" class="relative">
            <img loading="lazy" src="https://image.tmdb.org/t/p/w300{{ $episode['still_path'] }}"
                alt="{{ $episode['name'] }}"
                class="object-cover w-full h-32 p-0.5 transition duration-300 ease-in-out opacity-0 hover:ring-1 hover:ring-white"
                @load="loaded = true" :class="loaded ? 'opacity-100' : 'opacity-0'">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="absolute transition-transform duration-300 transform group-hover:scale-[1.15] bottom-2 left-2 size-6">
                    <path fill-rule="evenodd" d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" clip-rule="evenodd" />
                  </svg>
                  
        </a>

        <div class="px-1 py-2 text-xs rounded space-y-0.5">
            <!-- Title -->
            <h3 class="font-bold">E{{ $episode['episode_number'] }}:
                {{ $episode['name'] }}</h3>

            <div class="flex items-center gap-2">
                <!-- Age -->
                <p>{{ $series['adult'] ? '18+' : '13+' }}</p>
                <!-- Duration -->
                <p>
                    @if (floor($episode['runtime'] / 60) > 0)
                        {{ floor($episode['runtime'] / 60) }}h
                    @endif
                    @if ($episode['runtime'] % 60 > 0)
                        {{ $episode['runtime'] % 60 }}min
                    @endif
                </p>
                <!-- Air date -->
                <p> {{ Carbon\Carbon::parse($episode['air_date'])->format('Y') ?: '' }}</p>
            </div>
            <!-- Overview -->
            <p>
                {{ Str::words($episode['overview'], 20, '...') ?: '' }}
        </div>
    </div>
</div>
