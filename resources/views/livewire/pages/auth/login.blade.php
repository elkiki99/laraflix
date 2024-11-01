<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
}; ?>

<div>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block w-full mt-1" type="email" name="email"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="form.password" id="password" class="block w-full mt-1" type="password"
                name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div class="space-y-4">
            <x-primary-button class="justify-center w-full">
                {{ __('Log in') }}
            </x-primary-button>

            <p class="text-sm text-center text-gray-400">OR</p>

            <div>
                <a href="{{ route('register') }}" wire:navigate>
                    <x-secondary-button class="justify-center w-full">
                        {{ __('New to Laraflix? Register') }}
                    </x-secondary-button>
                </a>
            </div>
        </div>  

        <div class="flex items-center justify-between">
            <!-- Remember Me -->
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="text-indigo-600 bg-gray-900 border-gray-700 rounded shadow-sm focus:ring-offset-gray-800"
                    name="remember">
                <span class="text-sm text-gray-400 ms-2">{{ __('Remember me') }}</span>
            </label>

            <!-- Forgot your password? -->
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-400 underline rounded-md hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
    </form>
</div>
