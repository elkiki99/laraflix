@props(['episode', 'series', 'selectedSeason'])

<div x-data="{ loaded: true }"
    class="relative overflow-hidden bg-black rounded-sm shadow-md hover:cursor-pointer hover:shadow-xl">
    <div class="absolute inset-0 flex items-center justify-center" x-show="!loaded">
        <div class="w-8 h-8 border-4 border-gray-200 rounded-full border-t-gray-500 animate-spin">
        </div>
    </div>

    <div class="m-0.5">
        <!-- Image -->
        <a href="#">
            <img loading="lazy" src="https://image.tmdb.org/t/p/w300{{ $episode['still_path'] }}"
                alt="{{ $episode['name'] }}"
                class="object-cover w-full h-32 p-0.5 transition duration-300 ease-in-out opacity-0 hover:ring-1 hover:ring-white"
                @load="loaded = true" :class="loaded ? 'opacity-100' : 'opacity-0'">
        </a>

        <div class="px-1 py-2 text-xs rounded">
            <!-- Title -->
            <h3 class="font-bold ">E{{ $episode['episode_number'] }}:
                {{ $episode['name'] }}</h3>

            <div class="flex items-center gap-2 mt-2">
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
            <p class="mt-2 ">
                {{ Str::words($episode['overview'], 20, '...') ?: '.' }}
        </div>
    </div>
</div>
