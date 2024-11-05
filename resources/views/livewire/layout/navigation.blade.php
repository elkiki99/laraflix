<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public $class;

    public function logout(Logout $logout): void
    {   
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="{{ $class }} ">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('home') }}" wire:navigate>
                        <x-application-logo class="block w-auto text-gray-200 fill-current h-9" />
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                    {{ __('Home') }}
                </x-nav-link>
                <x-nav-link :href="route('movies.index')" :active="request()->routeIs('movies.index')" wire:navigate>
                    {{ __('Movies') }}
                </x-nav-link>
                <x-nav-link :href="route('series.index')" :active="request()->routeIs('series.index')" wire:navigate>
                    {{ __('Series') }}
                </x-nav-link>
            </div>

            <!-- Settings Dropdown -->
            <div class="flex items-center space-x-2 md:space-x-6 ms-6">
                <a class="{{ request()->routeIs('search') ? 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-100 focus:outline-none transition duration-150 ease-in-out' : 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-400 hover:text-gray-300 hover:border-gray-700 focus:outline-none focus:text-gray-300 transition duration-150 ease-in-out' }}"
                    href="{{ route('search') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </a>

                <a class="{{ request()->routeIs('watchlist') ? 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-100 focus:outline-none transition duration-150 ease-in-out' : 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-400 hover:text-gray-300 hover:border-gray-700 focus:outline-none focus:text-gray-300 transition duration-150 ease-in-out' }}"
                    href="{{ route(
                        'watchlist',
                        // , auth()->user()->id
                    ) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                        stroke="currentColor" class=" size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
                    </svg>
                </a>

                <x-dropdown align="right" width="48" class="bg-black bg-opacity-5">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-400 transition duration-150 ease-in-out bg-transparent border border-transparent rounded-md hover:text-gray-300 focus:outline-none">
                            {{-- <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"></div> --}}
                                <p>{{ auth()->user()->name }}</p>

                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="block sm:hidden">
                            <x-dropdown-link :href="route('movies.index')" wire:navigate>
                                {{ __('Movies') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('series.index')" wire:navigate>
                                {{ __('Series') }}
                            </x-dropdown-link>
                        </div>
                        
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        
                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-600">
            <div class="px-4">
                {{-- <div class="text-base font-medium text-gray-200" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name"></div> --}}
                    <p>{{ auth()->user()->name }}</p>
                <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
