<?php

use Livewire\Volt\Component;

new class extends Component {
    public $class = '';

}; ?>


<footer class="p-10 mt-auto {{ $class }}">
    <div class="flex flex-col items-center justify-center mx-auto space-y-2 text-center sm:space-y-0 sm:justify-between sm:text-start sm:flex-row max-w-7xl">
        <!-- Socials -->
        <div class="flex items-center space-x-4">
            <a href="https://facebook.com" target="_blank">
                <x-icons.facebook size="size-6" color="hover:text-gray-200 text-gray-400"
                    bgColor="{{ request()->is('movie/*') || request()->is('series/*') ? '#000000' : '#030712' }}" />
            </a>
            <a href="https://twitter.com" target="_blank">
                <x-icons.twitter size="size-4" color="hover:text-gray-200 text-gray-400" />
            </a>
            <a href="https://instagram.com" target="_blank">
                <x-icons.instagram size="size-4" color="hover:text-gray-200 text-gray-400" />
            </a>
            <a href="https://youtube.com" target="_blank">
                <x-icons.youtube size="size-5" color="hover:text-gray-200 text-gray-400"
                    bgColor="{{ request()->is('movie/*') || request()->is('series/*') ? '#000000' : '#030712' }}" />
            </a>
        </div>

        <!-- More info -->
        <div class="text-sm text-gray-400">
            &copy; {{ now()->year }} Laraflix. All rights reserved.
        </div>
    </div>
</footer>
